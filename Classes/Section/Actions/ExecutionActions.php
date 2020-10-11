<?php


namespace Classes\Section\Actions;

use core\Classes\Session;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;

use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\DataBase\Transaction;
use Lib\Miscs\Validation\SingleMisc;


/**
 *  Предназначен для исполнения действий для типа документа <i>Раздел</i>
 *
 */
class ExecutionActions extends MainExecutionActions
{

    /**
     * Действие <i>Создать описательную часть</i>
     *
     * @return string
     * @throws DataBaseEx
     * @throws PrimitiveValidatorEx
     * @throws SelfEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function action_1(): string
    {
        $this->checkParamsPOST('description', 'TEP_exist_flag', 'TEP', 'comments');

        $descriptionTable = $this->actions->typeOfObjectTableLocator->getDescriptivePartDescription();
        $TEPTable = $this->actions->typeOfObjectTableLocator->getDescriptivePartTEP();

        $transaction = new Transaction();

        // Описание раздела
        //
        $transaction->add($descriptionTable, 'deleteByIdMainDocumentAndIdAuthor', [CURRENT_DOCUMENT_ID, Session::getUserId()]);

        // При создании описания берется значение, не очищенное от html-тегов
        $transaction->add($descriptionTable, 'create', [
            CURRENT_DOCUMENT_ID,
            Session::getUserId(),
            $_POST['description']
        ]);

        // Технико-экономические показатели
        //
        if ($this->clearPOST['TEP_exist_flag'] == '1') {

            $TEPs = $this->primitiveValidator->getAssocArrayFromJson($this->clearPOST['TEP']);

            $settings = [
                'indicator' => ['is_string'],
                'measure'   => ['is_string'],
                'value'     => ['is_string'],
                'note'      => ['is_null', 'is_string']
            ];

            $transaction->add($TEPTable, 'deleteAllByIdMainDocumentAndIdAuthor', [CURRENT_DOCUMENT_ID, Session::getUserId()]);

            foreach ($TEPs as $TEP) {

                $this->primitiveValidator->validateAssociativeArray($TEP, $settings);

                $transaction->add($TEPTable, 'create', [
                    CURRENT_DOCUMENT_ID,
                    Session::getUserId(),
                    $TEP['indicator'],
                    $TEP['measure'],
                    $TEP['value'],
                    $TEP['note']
                ]);
            }
        }


        // Замечания
        //
        // Декодирование json'а
        try {
            //$comments = $this->primitiveValidator->getAssocArrayFromJson($this->clearPOST['comments']);

            $comments = [
                [
                    'id'                  => null,
                    'text'                => 'aaa',
                    'normative_document'  => 'bbb',
                    'note'                => '',
                    'comment_criticality' => '1',
                    'no_files'            => '0',
                    'files'               => ['1', '2', '3']
                ],
                [
                    'id'                  => 123,
                    'text'                => 'aaa',
                    'normative_document'  => 'bbb',
                    'note'                => 'ccc',
                    'comment_criticality' => '2',
                    'no_files'            => '1',
                    'files'               => []
                ]
            ];

        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с написанными замечаниями: {$e->getMessage()}", 6);
        }

        $docCommentTable = $this->actions->typeOfObjectTableLocator->getDocsComment();
        $attachedFileTable = $this->actions->typeOfObjectTableLocator->getCommentAttachedFiles();


        foreach ($comments as $comment) {

            try {

                $commentCriticality = new SingleMisc($comment['comment_criticality'], '\Tables\Miscs\comment_criticality');

                if (!is_array($comment['files'])) {

                    $type = gettype($comment['files']);
                    throw new SelfEx("'files' имеет тип: '{$type}', в то время как должен быть 'array'", 6);
                }

                $this->primitiveValidator->validateAssociativeArray($comment, [
                    'id'                  => ['is_null', [$this->primitiveValidator, 'validateNoEmptyString']],
                    'text'                => [[$this->primitiveValidator, 'validateNoEmptyString']],
                    'normative_document'  => [[$this->primitiveValidator, 'validateNoEmptyString']],
                    'note'                => ['is_null', [$this->primitiveValidator, 'validateNoEmptyString']],
                    'comment_criticality' => [[$commentCriticality, 'validate']],
                    'no_files'            => [[$this->primitiveValidator, 'validateSomeInclusions', null, '1']],
                    'files'               => [[$this->primitiveValidator, 'validateArrayValues', 'is_numeric']]
                ]);

                if (!$commentCriticality->isExist()) {
                    throw new SelfEx("Справочник критичности замечания является обязательным к заполнению", 6);
                }

                if (
                    $comment['no_files'] === '1'
                    && !empty($comment['files'])
                ) {
                    throw new SelfEx("Массив отмеченных файлов должен быть путым при выбранной опции: 'Отметка файлов не требуется'", 6);
                }

                // Создание записи замечания
                //$transaction->add

                // Создание записи файлов
                foreach ($comment['files'] as $file) {

                    //$transaction->add
                }


            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с замечаниями: {$e->getMessage()}", 6);
            }
        }



        $transaction->start();

        return 'todo';
    }
}