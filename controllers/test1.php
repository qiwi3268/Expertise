<?php

use Lib\DataBase\Transaction;
use Lib\Singles\DocumentTreeHandler;
use Lib\Responsible\Responsible;
use Tables\Docs\total_cc;
use Tables\assigned_expert_section_documentation_1;
use Tables\Docs\section_documentation_1;
use Tables\Docs\comment_documentation_1;

$totalCCId = 38;
$typeOfObjectId = 1;

// Временный контроллер для перевода проекта на стадию устранения замечаний
// --- искусственное действие для моделирования
// +++ действие, которое должно быть произойти у пользователя


// --------------------------------------------------------------------------------------
//  1 все назначенные эксперты совершили действие на разделе "Закончить работу с разделом"
// --------------------------------------------------------------------------------------

// --- получаем id разделов
$sectionIds = total_cc::getSectionIdsById($totalCCId, 1);
vd($sectionIds);



foreach ($sectionIds as $sectionId) {

    // --- получаем id назначенных на раздел экспертов
    $assignedExpertIds = assigned_expert_section_documentation_1::getExpertIdsByIdSection($sectionId);

    foreach ($assignedExpertIds as $expertId) {

//      { Действие "Закончить подготовку раздела"

            $transaction = new Transaction();

            // Завершаем работу с разделом (+)
            $transaction->add(
                '\Tables\assigned_expert_section_documentation_1',
                'setSectionPreparationFinishedByIdSectionAndIdExpert',
                [$sectionId, $expertId]
            );

            // Перевод всех замечаний на стадию "Замечание подготовлено"
            $transaction->add(
                '\Tables\Docs\comment_documentation_1',
                'updateIdStageByIdMainDocument',
                [2, $sectionId]
            );

            // Не осталось незакончивших работу с разделом экспертов
            if (!assigned_expert_section_documentation_1::checkExistWhereNotSectionPreparationFinishedByIdSection($sectionId)) {

                // Перевод раздела на стадию "Раздел подготовлен"
                $transaction->add(
                    '\Tables\Docs\section_documentation_1',
                    'updateIdStageById',
                    [2, $sectionId]
                );

                // Не осталось неподготовленных разделов
                if (!section_documentation_1::checkExistByIdMainDocumentAndIdStage($totalCCId, 2)) {

                    // Перевод сводного замечания / заключения на стадию "Подготовка сводного замечания ПТО"
                    $transaction->add(
                        '\Tables\Docs\total_cc',
                        'updateIdStageById',
                        [2, $totalCCId]
                    );

                    $responsible = new Responsible($sectionId, DOCUMENT_TYPE['total_cc']);

                    // Удаление предыдущих ответственных
                    $responsible->deleteCurrentResponsible($transaction, false);

                    // Установка отвественных сотрудников ПТО
                    $responsible->createResponsibleType2($transaction, ROLE_ID['EMP_PTO']);
                }
            }

            $transactionResults = $transaction->start()->getLastResults();
            vd($transactionResults);
            unset($transaction); // (-)
//      }
    }
}

//      { Действие пто по переводу на устранение замечаний (до этого подписания и т.д.)


//      }


// после того, как эксперт на разделе нажимает действие "Закончить работу с разделом" должен вызваться
// приватный метод проверки по переводу раздела со стадии "Подготовка раздела" на "Раздел подготовлен"

// на действии возобновить работу с разделом перевести все замечания на стадию "Подготовка замечания" одним запросом
// при добавлении замечания после выдачи не забыть ему нумерацию делать
