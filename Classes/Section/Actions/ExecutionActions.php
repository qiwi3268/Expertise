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
        $this->checkParamsPOST('description', 'TEP_exist_flag', 'TEP');

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

        $transaction->start();

        return true;
    }
}