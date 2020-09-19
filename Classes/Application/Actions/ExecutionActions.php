<?php


namespace Classes\Application\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Responsible as ResponsibleEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;

use core\Classes\Session;
use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\Responsible\Responsible;
use Lib\DataBase\Transaction;
use Tables\Docs\application;
use Tables\user;


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
     * @throws ResponsibleEx
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

        // Проверка назначенных экспертов
        $leadCount = arrayEntry($experts, 'lead', true)['count'];
        if ($leadCount != 1) {
            throw new SelfEx("Количество ведущих экспертов: {$leadCount}, в то время как должно быть 1", 6);
        }
        if (arrayEntry($experts, 'common_part', true)['count'] == 0) {
            throw new SelfEx("Количество экспертов на общую часть равно 0", 6);
        }

        $tables = [
            'doc_total_cc' => '\Tables\Docs\total_cc',
            'assigned_expert_total_cc' => '\Tables\assigned_expert_total_cc',
            'resp_total_cc' => '\Tables\Responsible\type_4\total_cc'
        ];

        // Определение вида объекта
        $typeOfObjectId = application::getIdTypeOfObjectById(CURRENT_DOCUMENT_ID);

        switch ($typeOfObjectId) {
            case 1 : // Производственные / непроизводственные
                $tables['assigned_expert_main_block_341'] = '\Tables\order_341\documentation_1\assigned_expert';
                $tables['doc_section'] = '\Tables\Docs\section_documentation_1';
                $tables['resp_section'] = '\Tables\Responsible\type_4\section_documentation_1';
                break;
            case 2 : // Линейные
                $tables['assigned_expert_main_block_341'] = '\Tables\order_341\documentation_2\assigned_expert';
                $tables['doc_section'] = '\Tables\Docs\section_documentation_2';
                $tables['resp_section'] = '\Tables\Responsible\type_4\section_documentation_2';
                break;
            default :
                throw new SelfEx("Заявление имеет неопределенный вид объекта: '{$typeOfObjectId}'", 6);
        }

        $transaction = new Transaction();

        // Создание сводного замечания / заключения
        $transaction->add(
            $tables['doc_total_cc'],
            'create',
            [CURRENT_DOCUMENT_ID, Session::getUserId()],
            'id_total_cc'
        );

        // Индексный массив с id экспертов
        $expertsId = [];

        // Создание массива, по которому будут создаваться разделы и назначаться эксперты
        // Индексный массив формата:
        // Ключ - id блока из 341 приказа
        // Значение - индексный массив формата:
        //    Ключ - простой порядковый индекс
        //    значение - id эксперта
        $mainBlocks = [];

        foreach ($experts as $expert) {

            $expertsId[] = $expert['id_expert'];

            // Создание назначенных экспертов к созданному ранее сводному замечанию / заключению
            $transaction->add(
                $tables['assigned_expert_total_cc'],
                'create',
                [
                    $expert['id_expert'],
                    $expert['lead'] ? 1 : 0,
                    $expert['common_part'] ? 1 : 0
                ],
                null,
                'id_total_cc'
            );

            foreach ($expert['ids_main_block_341'] as $id_main_block) {

                // Создание записей на какие блоки из 341 приказа был назначен итерируемый эксперт
                $transaction->add(
                    $tables['assigned_expert_main_block_341'],
                    'create',
                    [$id_main_block, $expert['id_expert']],
                    null,
                    'id_total_cc'
                );

                $mainBlocks[$id_main_block][] = $expert['id_expert'];
            }
        }

        // Назначение всех экспертов ответственными на сводное замечание / заключение
        foreach ($expertsId as $id) {
            
            $transaction->add(
                $tables['resp_total_cc'],
                'create',
                [$id],
                null,
                'id_total_cc'
            );
        }

        $sectionCount = 1; // Счетчик для динамического связывания разделов и ответственных экспертов

        foreach ($mainBlocks as $id_main_block => $assignedExpertsId) {

            // Создание разделов
            $transaction->add(
                $tables['doc_section'],
                'create',
                [$id_main_block],
                "id_section_{$sectionCount}",
                'id_total_cc'
            );

            // Назначение определенных экспертов ответственными на раздел
            foreach ($assignedExpertsId as $expertId) {

                $transaction->add(
                    $tables['resp_section'],
                    'create',
                    [$expertId],
                    null,
                    "id_section_{$sectionCount}"
                );
            }
            $sectionCount++;
        }

        $transaction->start();


        return 'todo';
        // todo поменять стадию на заявлении
        // todo ???КД на заявлении
        // todo ??? ответственные на заявлении
        // todo ???КД на сводном
        
    }
}