<?php


namespace Lib\CommentsManager;

use Lib\Exceptions\CommentsManager as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\Responsible as ResponsibleEx;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use Lib\DataBase\Transaction;
use Lib\Responsible\Responsible;
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
     * id раздела
     *
     * id из doc_section_documentation_1 или doc_section_documentation_2
     *
     */
    private int $sectionId;

    /**
     * id вида объекта
     *
     */
    private int $typeOfObjectId;

    /**
     * id автора
     *
     * id из `user`, по которому будут создавать новые замечания и обрабатываться старые
     *
     */
    private int $authorId;

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
     * Таблица с файлами документации в зависимости от вида объекта
     *
     */
    private string $documentationTable;


    /**
     * Конструктор класса
     *
     * @param array $comments
     * @param Transaction $transaction
     * @param int $sectionId id раздела, в контексте которого ведется работа с замечаниями
     * @param int $typeOfObjectId id вида объекта
     * @param int $authorId id автора, по которому будут создавать новые замечания
     * и обрабатываться старые
     * @throws SelfEx
     * @throws MiscValidatorEx выбрасывается в методе {@see \Lib\Singles\PrimitiveValidator::validateAssociativeArray()}
     * @throws TablesEx
     * @throws DataBaseEx
     *
     */
    public function __construct(
        array $comments,
        Transaction $transaction,
        int $sectionId,
        int $typeOfObjectId,
        int $authorId
    ) {
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
                        'comment_criticality' => [[$primitiveValidator, 'validateNoEmptyString']],
                        'files'               => [[$primitiveValidator, 'validateArrayValues', 'is_numeric']]
                    ]
                );
            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с замечаниями: {$e->getMessage()}", 2);
            }

            $commentCriticality = new SingleMisc($comment['comment_criticality'], '\Tables\Miscs\comment_criticality');
            $commentCriticality->validate();

            if (!$commentCriticality->isExist()) {

                throw new SelfEx("Справочник критичности замечания является обязательным к заполнению", 3);
            }

            if ($comment['no_files'] === '1' && !empty($comment['files'])) {

                throw new SelfEx("Массив отмеченных файлов должен быть пустым при выбранной опции: 'Отметка файлов не требуется'", 4);
            } elseif (is_null($comment['no_files']) && empty($comment['files'])) {

                throw new SelfEx("Массив отмеченных файлов не должен быть пустым, если не выбрана опция: 'Отметка файлов не требуется'", 5);
            }

            if (is_null($comment['id'])) {
                $nullComments[] = $comment;
            } else {
                $notNullComments[] = $comment;
            }

            $hashes[] = $comment['hash'];
        }

        if (!$primitiveValidatorBoolWrapper->checkUniquenessArrayValues($hashes)) {

            throw new SelfEx("Присутствуют повторяющиеся hash'и замечаний", 6);
        }

        $this->transaction = $transaction;

        $this->sectionId = $sectionId;
        $this->typeOfObjectId = $typeOfObjectId;
        $this->authorId = $authorId;

        $this->typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);
        $this->primitiveValidator = $primitiveValidator;
        $this->primitiveValidatorBoolWrapper = $primitiveValidatorBoolWrapper;

        $this->nullComments = $nullComments;
        $this->notNullComments = $notNullComments;

        $this->docCommentTable = $this->typeOfObjectTableLocator->getDocsComment();
        $this->responsibleType4Table = $this->typeOfObjectTableLocator->getResponsibleType4Comment();
        $this->attachedFileTable = $this->typeOfObjectTableLocator->getCommentAttachedFiles();
        $this->documentationTable = $this->typeOfObjectTableLocator->getFilesDocumentation();
    }


    /**
     * Предназначен для получения таблицы документа замечания
     *
     * @return string
     */
    public function getDocCommentTable(): string
    {
        return $this->docCommentTable;
    }


    /**
     * Массив с замечаниями, у которых поле 'id' - null
     *
     * @return array
     */
    public function getNullComments(): array
    {
        return $this->nullComments;
    }


    /**
     * Предназначен для создания замечаний
     *
     * Выполняет следующие операции:
     * - Создание записей в таблице документа "Замечание"
     * - Создание записей в таблице прикрепленных к замечанию файлов
     * - Назначение пользователя ответственным
     *
     * @return $this
     * @throws TransactionEx
     * @throws ReflectionException
     * @throws SelfEx
     */
    public function create(): self
    {
        $authorId = $this->authorId;

        $count = 1; // Счетчик для создания уникальных ключей

        foreach ($this->nullComments as $comment) {

            // Создание записи замечания
            $this->transaction->add(
                $this->docCommentTable,
                'create',
                [
                    $this->sectionId,
                    $authorId,
                    $comment['text'],
                    $comment['normative_document'],
                    is_null($comment['no_files']) ? 0 : 1,
                    $comment['note'],
                    $comment['comment_criticality']
                ],
                "comment_id_{$count}"
            );

            // Назначение пользователя ответственным на замечание
            $this->transaction->add(
                $this->responsibleType4Table,
                'create',
                [$authorId],
                null,
                "comment_id_{$count}"
            );

            if (is_null($comment['no_files'])) {

                // Создание записи файлов
                foreach ($comment['files'] as $fileId) {

                    // Проверка существования файла
                    $this->checkFileExist($fileId);

                    $this->transaction->add(
                        $this->attachedFileTable,
                        'create',
                        [$authorId, $fileId],
                        null,
                        "comment_id_{$count}"
                    );
                }
            }
            $count++;
        }
        return $this;
    }


    /**
     * Предназначен для обновления замечаний
     *
     * Выполняет следующие операции:
     * - Обновление все полей в БД к записи замечания
     * - Удаление / создание записей в таблице прикрепленных к замечанию файлов
     *
     * @return $this
     * @throws ReflectionException
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function update(): self
    {
        foreach ($this->notNullComments as $comment) {

            $commentId = $comment['id'];

             // Проверка существования замечания в БД
            if (!$this->docCommentTable::checkExistById($commentId)) {
                throw new SelfEx("Запись замечания, находящаяся во входном json'е с id: '{$commentId}', не существует в БД", 8);
            }

            // Обновление всех полей в записи замечания
            $this->transaction->add(
                $this->docCommentTable,
                'updateById',
                [
                    $commentId,
                    $comment['text'],
                    $comment['normative_document'],
                    is_null($comment['no_files']) ? 0 : 1,
                    $comment['note'],
                    $comment['comment_criticality']
                ]
            );

            // Вычисление прикрепленных файлов, записи которых нужно удалить / создать
            //
            // id прикрепленных файлов к замечанию из БД
            $db_attachedFilesId = $this->attachedFileTable::getIdFilesByIdMainDocument($commentId) ?? [];
            // id прикрепленных файлов к замечанию из входного json'а
            $js_attachedFilesId = $comment['files'];


            list(
                'delete' => $toDelete,
                'create' => $toCreate
                ) = calculateDeleteAndCreateIds($db_attachedFilesId, $js_attachedFilesId);

            foreach ($toDelete as $fileId) {

                $this->transaction->add(
                    $this->attachedFileTable,
                    'deleteByIdMainDocumentAndIdFile',
                    [$commentId, $fileId]
                );
            }

            foreach ($toCreate as $fileId) {

                // Проверка существования файла
                $this->checkFileExist($fileId);

                $this->transaction->add(
                    $this->attachedFileTable,
                    'create',
                    [
                        $commentId,
                        $this->authorId,
                        $fileId
                    ]
                );
            }
        }
        return $this;
    }


    /**
     * Предназначен для удаления замечаний
     *
     * Выполняет следующие операции:
     * - Удаление записей в таблице документа "Замечание"
     * - Удаление записей в таблице прикрепленных к замечанию файлов
     * - Удаление текущих ответственных пользователей к замечанию
     *
     * @return $this
     * @throws ReflectionException
     * @throws ResponsibleEx
     * @throws SelfEx
     * @throws TransactionEx
     */
    public function delete(): self
    {
        $db_docCommentEntriesId = $this->docCommentTable::getIdsByIdMainDocumentAndIdAuthor($this->sectionId, $this->authorId) ?? [];
        $js_docCommentEntriesId = compressArrayValuesByKey($this->notNullComments, 'id');

        list(
            'delete' => $toDelete,
            'create' => $toCreate
            ) = calculateDeleteAndCreateIds($db_docCommentEntriesId, $js_docCommentEntriesId);

        // Каждая запись из входного json'а должна существовать в полученной из выборке БД
        if (!empty($toCreate)) {
            $debug = implode(', ', $toCreate);
            throw new SelfEx("Во входном json'e присутствуют замечания с id: '{$debug}', которых нет в БД", 9);
        }

        $commentDocumentType = DOCUMENT_TYPE_BY_TYPE_OF_OBJECT_ID['comment'][$this->typeOfObjectId];

        foreach ($toDelete as $commentId) {

            // Удаление прикрепленных файлов к замечанию
            //
            $this->transaction->add(
                $this->attachedFileTable,
                'deleteAllByIdMainDocument',
                [$commentId]
            );

            // Удаление ответственных с замечания
            //
            $responsible = new Responsible($commentId, $commentDocumentType);
            $responsible->deleteCurrentResponsible($this->transaction, false);

            // Удаление записей замечания
            // Удаление замечания должно идти после удаления прикрепленных файлов и ответственных, т.к.
            // иначе будет ошибка внешних ключей
            //
            $this->transaction->add(
                $this->docCommentTable,
                'deleteById',
                [$commentId]
            );
        }
        return $this;
    }


    /**
     * Предназначен для проверки существования записи файла по его id
     *
     * @param int $fileId id файла
     * @throws SelfEx
     */
    private function checkFileExist(int $fileId): void
    {
        if (!$this->documentationTable::checkExistById($fileId)) {

            throw new SelfEx("Запись файла с id:'{$fileId}' не существует в таблице класса: '{$this->documentationTable}'", 7);
        }
    }
}