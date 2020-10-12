<?php


namespace Classes\Section\Actions;

use core\Classes\Session;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\CommentsManager as CommentsManagerEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;
use Exception;

use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\Actions\ExecutionActionsResult;
use Lib\DataBase\Transaction;
use Lib\Miscs\Validation\SingleMisc;
use Lib\CommentsManager\CommentsManager;


/**
 * Предназначен для исполнения действий для типа документа <i>Раздел</i>
 *
 */
class ExecutionActions extends MainExecutionActions
{

    /**
     * Действие <i>Создать описательную часть</i>
     *
     * @return ExecutionActionsResult
     * @throws DataBaseEx
     * @throws PrimitiveValidatorEx
     * @throws SelfEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function action_1(): ExecutionActionsResult
    {
        $this->checkParamsPOST('description', 'TEP_exist_flag', 'TEP', 'comments');

        $descriptionTable = $this->actions->typeOfObjectTableLocator->getDescriptivePartDescription();
        $TEPTable = $this->actions->typeOfObjectTableLocator->getDescriptivePartTEP();

        $userId = Session::getUserId();

        $transaction = new Transaction();

        // Описание раздела
        //
        $transaction->add($descriptionTable, 'deleteByIdMainDocumentAndIdAuthor', [CURRENT_DOCUMENT_ID, $userId]);

        // При создании описания берется значение, не очищенное от html-тегов
        $transaction->add($descriptionTable, 'create', [
            CURRENT_DOCUMENT_ID,
            $userId,
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

            $transaction->add($TEPTable, 'deleteAllByIdMainDocumentAndIdAuthor', [CURRENT_DOCUMENT_ID, $userId]);

            foreach ($TEPs as $TEP) {

                $this->primitiveValidator->validateAssociativeArray($TEP, $settings);

                $transaction->add($TEPTable, 'create', [
                    CURRENT_DOCUMENT_ID,
                    $userId,
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
            $comments = $this->primitiveValidator->getAssocArrayFromJson($this->clearPOST['comments']);
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с написанными замечаниями: {$e->getMessage()}", 3005);
        }

        try {
            $commentsManager = new CommentsManager($comments, $transaction, CURRENT_DOCUMENT_ID, $this->actions->typeOfObjectId, $userId);
            $commentsManager->delete()->create()->update();

        } catch (CommentsManagerEx $e) {
            throw new SelfEx("Ошибка класса управления массивом замечаний. Message: '{$e->getMessage()}'. Code: '{$e->getCode()}'", 3005);
        } catch (Exception $e) {
            throw new SelfEx("Ошибка при обработке замечаний. Message: '{$e->getMessage()}'. Code: '{$e->getCode()}'", 3005);
        }


        //$transaction->start();

        $methodResult = new ExecutionActionsResult('todo');
        return $methodResult;
    }
}