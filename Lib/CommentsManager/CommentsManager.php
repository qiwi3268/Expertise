<?php


namespace Lib\CommentsManager;

use Lib\Exceptions\CommentsManager as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use Lib\DataBase\Transaction;
use Lib\Miscs\Validation\SingleMisc;
use Lib\Singles\PrimitiveValidator;
use Lib\Singles\PrimitiveValidatorBoolWrapper;
use Tables\Locators\TypeOfObjectTableLocator;



/**
 * Предназначен для управления массивом замечаний, полученных с клиентского js
 *
 * - Выполняет валидацию массива замечаний, полученного декодированием json-строки
 * - Выполняет действия по созданию / обновлению / удалению замечаний
 *
 */
class CommentsManager
{
    /**
     * Объект транзации, в которую уже могли быть добавленны данные для выполнения запросов
     *
     */
    private Transaction $transaction;

    /**
     * Экземпляр класса {@see \Tables\Locators\TypeOfObjectTableLocator}
     *
     */
    private TypeOfObjectTableLocator $typeOfObjectTableLocator;

    /**
     * Экземпляр класса {@see \Lib\Singles\PrimitiveValidator}
     *
     */
    private PrimitiveValidator $primitiveValidator;

    /**
     * Экземпляр класса {@see \Lib\Singles\PrimitiveValidatorBoolWrapper}
     *
     */
    private PrimitiveValidatorBoolWrapper $primitiveValidatorBoolWrapper;

    /**
     * Массив с замечаниями, у которых поле 'id' - null.
     *
     * Это замечания, которые впервые попали на сервер
     *
     */
    private array $nullComments = [];

    /**
     * Массив с замечаниями, у которых поле 'id' - НЕ null.
     *
     * Это замечания, которые уже были обработаны на сервере
     *
     */
    private array $notNullComments = [];

    /**
     * Таблица документа замечания в зависимости от вида объекта
     *
     */
    private string $docCommentTable;

    /**
     * Таблица ответственных пользователей к замечанию в зависимости от вида объекта
     *
     */
    private string $responsibleType4Table;

    /**
     * Таблица прикрепленных файлов к замечанию в зависимости от вида объекта
     *
     */
    private string $attachedFileTable;


    /**
     * Конструктор класса
     *
     * @param array $comments
     * @param Transaction $transaction
     * @param int $typeOfObjectId
     * @throws SelfEx
     * @throws MiscValidatorEx
     * @throws TablesEx
     *
     */
    public function __construct(array $comments, Transaction $transaction, int $typeOfObjectId)
    {
        $primitiveValidator = new PrimitiveValidator();
        $primitiveValidatorBoolWrapper = new PrimitiveValidatorBoolWrapper($primitiveValidator);

        $nullComments = [];
        $notNullComments = [];

        // Массив с хэшами замечаний
        $hashes = [];

        foreach ($comments as $comment) {

            if (!is_array($comment['files'])) {

                $type = gettype($comment['files']);
                throw new SelfEx("'files' имеет тип: '{$type}', в то время как должен быть 'array'", 1);
            }

            $commentCriticality = new SingleMisc($comment['comment_criticality'], '\Tables\Miscs\comment_criticality');

            try {

                $primitiveValidator->validateAssociativeArray(
                    $comment,
                    [
                        'id'                  => ['is_null', [$primitiveValidator, 'validateNoEmptyString']],
                        'hash'                => ['is_int'],
                        'text'                => [[$primitiveValidator, 'validateNoEmptyString']],
                        'normative_document'  => [[$primitiveValidator, 'validateNoEmptyString']],
                        'no_files'            => [[$primitiveValidator, 'validateSomeInclusions', null, '1']],
                        'note'                => ['is_null', [$primitiveValidator, 'validateNoEmptyString']],
                        'comment_criticality' => [[$commentCriticality, 'validate']],
                        'files'               => [[$primitiveValidator, 'validateArrayValues', 'is_numeric']]
                    ]
                );
            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с замечаниями: {$e->getMessage()}", 2);
            }

            if (!$commentCriticality->isExist()) {

                throw new SelfEx("Справочник критичности замечания является обязательным к заполнению", 3);
            }

            if (
                $comment['no_files'] === '1'
                && !empty($comment['files'])
            ) {
                throw new SelfEx("Массив отмеченных файлов должен быть пустым при выбранной опции: 'Отметка файлов не требуется'", 4);
            }

            if (is_null($comment['id'])) {
                $nullComments[] = $comment;
            } else {
                $notNullComments[] = $comment;
            }

            $hashes[] = $comment['hash'];
        }

        if (!$primitiveValidatorBoolWrapper->checkUniquenessArrayValues($hashes)) {

            throw new SelfEx("Присутствуют повторяющиеся hash'и замечаний", 5);
        }

        $this->transaction = $transaction;
        $this->typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);
        $this->primitiveValidator = $primitiveValidator;
        $this->primitiveValidatorBoolWrapper = $primitiveValidatorBoolWrapper;

        $this->docCommentTable = $this->typeOfObjectTableLocator->getDocsComment();
        $this->responsibleType4Table = $this->typeOfObjectTableLocator->getResponsibleType4Comment();
        $this->attachedFileTable = $this->typeOfObjectTableLocator->getCommentAttachedFiles();
    }


    /**
     * Предназначен для создания замечаний
     *
     * Выполняет следующие операции
     * - Создание записей в таблице документа "Замечание"
     * - Создание записей в таблице прикрепленных к замечанию фапйлов
     * - Назначение пользователя ответственным
     *
     * @param int $mainDocumentId id главного документа.
     * id из doc_section_documentation_1 или doc_section_documentation_2
     * @param int $authorId id автора
     * @return $this
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function create(int $mainDocumentId, int $authorId): self
    {
        foreach ($this->nullComments as $comment) {

            // Создание записи замечания
            $this->transaction->add(
                $this->docCommentTable,
                'create',
                [
                    $mainDocumentId,
                    $authorId,
                    $comment['text'],
                    $comment['normative_document'],
                    $comment['no_files'],
                    $comment['note'],
                    $comment['comment_criticality']
                ],
                'id_comment'
            );

            // Назначение пользователя ответственным на замечание
            $this->transaction->add(
                $this->responsibleType4Table,
                'create',
                [$authorId],
                null,
                'id_comment'
            );

            if (is_null($comment['no_files'])) {

                // Создание записи файлов
                foreach ($comment['files'] as ['id' => $fileId]) {

                    $this->transaction->add(
                        $this->attachedFileTable,
                        'create',
                        [$authorId, $fileId],
                        null,
                        'id_comment'
                    );
                }
            }
        }
        return $this;
    }


    public function update(): self
    {

        foreach ($this->notNullComments as $comment) {

            // Проверка существования замечания в БД (без транзакции)
            $commentId = $comment['id'];

            if (!$this->docCommentTable::checkExistById($commentId)) {
                throw new SelfEx("Запись замечания, находящаяся во входном json'е с id: '{$commentId}', не существует в БД", 6);
            }

            // Обновление все полей в записи замечания
            $this->transaction->add(
                $this->docCommentTable,
                'updateById',
                [
                    $commentId,
                    $comment['text'],
                    $comment['normative_document'],
                    $comment['no_files'],
                    $comment['note'],
                    $comment['comment_criticality']
                ]
            );

            // todo вычисление файлов, которые требуется удалить
            // todo написать метод в аттач файл тейбл getIdsByIdMainDocument

        }
        return $this;
    }
}