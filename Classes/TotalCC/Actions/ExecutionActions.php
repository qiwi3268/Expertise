<?php


namespace Classes\TotalCC\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\DataBase\Transaction;
use Lib\Singles\FinancingSourcesHandler;
use Lib\Singles\Helpers\PageAddress;
use Tables\FinancingSources\FinancingSourcesAggregator;


class ExecutionActions extends MainExecutionActions
{

    // Реализация callback'ов исполнения действий из БД

    // Ошибкам во время исполнения действия необходимо присваивать code 6


    /**
     * Действие <i>"Создать общую часть"</i>
     *
     * @return string
     * @throws DataBaseEx
     * @throws MiscValidatorEx
     * @throws SelfEx
     * @throws TransactionEx
     * @throws ReflectionException
     * @throws TablesEx
     */
    public function action_1(): string
    {

        try {

            $financingSourcesHandler = new FinancingSourcesHandler($this->getRequiredPOSTParameter('financing_sources'));

            $financingSourcesHandler->validateArray();
        } catch (PrimitiveValidatorEx $e) {

            throw new SelfEx("Произошла при декодировании / валидации входной json-строки с источниками финансирования: {$e->getMessage()}, {$e->getCode()}", 6);
        }

        $financingSourcesAggregator = new FinancingSourcesAggregator(FinancingSourcesAggregator::COMMON_PART_TABLE_TYPE, CURRENT_DOCUMENT_ID);


        $transaction = new Transaction();

        $transaction->add($financingSourcesAggregator, 'deleteAll');
        $transaction->add($financingSourcesAggregator, 'createByArray', [$financingSourcesHandler->getArray()]);

        $transaction->start();

        $test = $transaction->getLastResults();
        //todo флаг о создании общей части или из истории?
        return PageAddress::createCardRef(CURRENT_DOCUMENT_ID, 'total_cc', 'view');
    }






    // редактирование
    public function action_2(): string
    {
        //todo удалять источников финансирования записи из таблицы
    }
}