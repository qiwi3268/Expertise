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


class ExecutionActions extends MainExecutionActions
{

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
        //
        try {
            $experts = $this->primitiveValidator->getAssocArrayFromJson($this->getRequiredPOSTParameter('experts'));
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с назначенными экспертами : {$e->getMessage()}", 6);
        }

        // Валидация входного массива
        //
        foreach ($experts as $expert) {

            try {

                $this->primitiveValidator->validateAssociativeArray($expert, [
                    'id_expert'          => [[$this->primitiveValidator, 'validateInt']],
                    'lead'               => ['is_bool'],
                    'common_part'        => ['is_bool'],
                    'ids_main_block_341' => ['is_array']
                ]);

                foreach ($expert['ids_main_block_341'] as $id) {
                    $this->primitiveValidator->validateInt($id);
                }
            } catch (PrimitiveValidatorEx $e) {

                throw new SelfEx("Произошла ошибка при валидации массива с назначенными экспертами : {$e->getMessage()}", 6);
            }
        }

        // Проверка на то, что есть ведущий и он один. Проверка на то, что есть назначенный на общую часть

        $transaction = new Transaction();
        $transaction->add('\Tables\Docs\total_cc', 'create', [CURRENT_DOCUMENT_ID, Session::getUserId()], true);

        foreach ($experts as $expert) {

            $transaction->add('\Tables\assigned_expert_total_tc', 'create', [
                $expert['id_expert'],
                $expert['lead'] ? 1 : 0,
                $expert['common_part'] ? 1 : 0
            ], false, true);
        }


        // todo привязать экспертов к разделам
        // todo поменять стадию на заявлении

        // todo ???КД на заявлении
        // todo ??? ответственные на заявлении
        // todo ???КД на сводном

        // todo ответственные на сводном

        $transaction->start();


        return 'todo';
    }
}