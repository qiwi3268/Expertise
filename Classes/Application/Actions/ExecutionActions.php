<?php


namespace Classes\Application\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use core\Classes\Session;
use Lib\Actions\ExecutionActions as MainExecutionActions;
use Lib\Actions\ExecutionActionsResult;
use Lib\DataBase\Transaction;
use Lib\Singles\Helpers\PageAddress;
use Tables\Docs\application;
use Tables\Locators\TypeOfObjectTableLocator;


/**
 *  Предназначен для исполнения действий для типа документа <i>Заявление</i>
 *
 */
class ExecutionActions extends MainExecutionActions
{

    public function action_1(): ExecutionActionsResult
    {
        return new ExecutionActionsResult('todo');
    }


    /**
     * Действие <i>Назначить экспертов</i>
     *
     * @return ExecutionActionsResult
     * @throws SelfEx
     * @throws TablesEx
     * @throws TransactionEx
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    public function action_2(): ExecutionActionsResult
    {

        // Декодирование json'а
        try {
            $experts = $this->primitiveValidator->getAssocArrayFromJson($this->getRequiredPOSTParameter('experts'));
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Произошла ошибка при декодировании входной json-строки с назначенными экспертами: {$e->getMessage()}", 3005);
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

                throw new SelfEx("Произошла ошибка при валидации массива с назначенными экспертами: {$e->getMessage()}", 3005);
            }
        }

        // Проверка назначенных экспертов
        $leadCount = arrayEntry($experts, 'lead', true)['count'];
        if ($leadCount != 1) {
            throw new SelfEx("Количество ведущих экспертов: {$leadCount}, в то время как должно быть 1", 3005);
        }
        if (arrayEntry($experts, 'common_part', true)['count'] == 0) {
            throw new SelfEx("Количество экспертов на общую часть равно 0", 3005);
        }

        $tableLocator = new TypeOfObjectTableLocator(application::getIdTypeOfObjectById(CURRENT_DOCUMENT_ID));

        $tables = [
            // Создание сводного замечания / заключения
            'doc_total_cc'             => '\Tables\Docs\total_cc',
            // Назначенные эксперты на сводное замечание / заключение (включая информацию ведущий / общая часть)
            'assigned_expert_total_cc' => '\Tables\assigned_expert_total_cc',
            // Назначение ответственных на сводное замечание / заключение
            'resp_total_cc'            => '\Tables\Responsible\type_4\total_cc',
            // Создание разделов
            'doc_section'              => $tableLocator->getDocsSection(),
            // Назначение ответственных на каждый из разделов
            'resp_section'             => $tableLocator->getResponsibleType4Section(),
            // Связь эксперта и разделов, на которые он был назначен
            'assigned_expert_section'  => $tableLocator->getAssignedExpertSection()
        ];

        // resp_section и assigned_expert_section похожи, но ответственные на разделе в ходе процесса будут меняться
        // с заявителем, а назначенные на раздел остаются навсегда (если не будут заменены)

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
        //    Значение - id эксперта
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

            foreach ($assignedExpertsId as $expertId) {

                // Назначение определенных экспертов ответственными на раздел
                $transaction->add(
                    $tables['resp_section'],
                    'create',
                    [$expertId],
                    null,
                    "id_section_{$sectionCount}"
                );

                // Создание записей на какие разделы был назначен итерируемый эксперт
                $transaction->add(
                    $tables['assigned_expert_section'],
                    'create',
                    [$expertId],
                    null,
                    "id_section_{$sectionCount}"
                );
            }
            $sectionCount++;
        }
        $transactionResults = $transaction->start()->getLastResults();

        $totalCCId = $transactionResults[$tables['doc_total_cc']]['create'][0];


        // todo поменять стадию на заявлении
        // todo ???КД на заявлении
        // todo ??? ответственные на заявлении
        // todo ???КД на сводном

        $methodResult = new ExecutionActionsResult(PageAddress::createCardRef($totalCCId, 'total_cc', 'view'));
        return $methodResult;
    }
}