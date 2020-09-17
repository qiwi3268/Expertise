<?php


namespace Classes\Application\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;

use core\Classes\Session;
use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\DataBase\Transaction;
use Tables\Docs\application;


/**
 *  Предназначен для исполнения действий для типа документа <i>Заявление</i>
 *
 */
class ExecutionActions extends MainExecutionActions
{
    // Реализация callback'ов исполнения действий из БД
    // Ошибкам во время исполнения действия необходимо присваивать code 6


    public function action_1(): string
    {
        return true;
    }


    /**
     * Действие <i>"Назначить экспертов"</i>
     *
     * @return string
     * @throws SelfEx
     * @throws TransactionEx
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    public function action_2(): string
    {

        // Декодирование json'а
        try {
            $experts = $this->primitiveValidator->getAssocArrayFromJson($this->getRequiredPOSTParameter('experts'));
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с назначенными экспертами : {$e->getMessage()}", 6);
        }

        // Валидация входного массива
        foreach ($experts as $expert) {

            try {

                $this->primitiveValidator->validateAssociativeArray($expert, [
                    'id_expert' => [[$this->primitiveValidator, 'validateInt']],
                    'lead' => ['is_bool'],
                    'common_part' => ['is_bool'],
                    'ids_main_block_341' => ['is_array']
                ]);

                foreach ($expert['ids_main_block_341'] as $id) {
                    $this->primitiveValidator->validateInt($id);
                }
            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с назначенными экспертами : {$e->getMessage()}", 6);
            }
        }

        // Проверка назначенных экспертов
        $leadCount = arrayEntry($experts, 'lead', true)['count'];
        if ($leadCount != 1) {
            throw new SelfEx("Количество ведущих экспертов: {$leadCount}, в то время как должно быть 1", 6);
        }
        if (arrayEntry($experts, 'common_part', true)['count'] == 0) {
            throw new SelfEx("Количество экспертов на общую часть равно 0", 6);
        }

        $transaction = new Transaction();

        // Создание сводного замечания / заключения
        // Устанавливаем id созданного документа в передачу по цепочке
        $transaction->add('\Tables\Docs\total_cc', 'create', [CURRENT_DOCUMENT_ID, Session::getUserId()], true);

        // Создание назначенных экспертов к созданному ранее сводному замечанию / заключению
        foreach ($experts as $expert) {

            $transaction->add('\Tables\assigned_expert_total_tc', 'create', [
                $expert['id_expert'],
                $expert['lead'] ? 1 : 0,
                $expert['common_part'] ? 1 : 0
            ], false, true);
        }

        // Определение Вида работ
        $typeOfObjectId = application::getIdTypeOfObjectById(CURRENT_DOCUMENT_ID);

        switch ($typeOfObjectId) {
            case 1 : // Производственные/непроизводственные
                $tableName = '\Tables\order_341\documentation_1\assigned_expert';
                break;
            case 2 : // Линейные
                $tableName = '\Tables\order_341\documentation_2\assigned_expert';
                break;
            default :
                throw new SelfEx("Заявление имеет неопределенный Вид работ: '{$typeOfObjectId}'", 6);
        }

        // Создание записей на какие разделы из 341 приказа были назначены эксперты
        foreach ($experts as $expert) {

            foreach ($expert['ids_main_block_341'] as $id_main_block) {

                $transaction->add(
                    $tableName,
                    'create',
                    [$id_main_block, $expert['id_expert']],
                    false,
                    true
                );
            }
        }


        // todo поменять стадию на заявлении

        // todo ???КД на заявлении
        // todo ??? ответственные на заявлении
        // todo ???КД на сводном

        // todo ответственные на сводном

        $transaction->start();


        return 'todo';
    }
}