<?php

use Lib\DataBase\Transaction;
use Lib\Singles\DocumentTreeHandler;
use Lib\Responsible\Responsible;
use Tables\Docs\total_cc;
use Tables\AssignedExperts\total_cc as assigned_expert_total_cc;
use Tables\Docs\section_documentation_1;
use Tables\Docs\applicant_documentation_1;
use Tables\Locators\TypeOfObjectTableLocator;



// Временный контроллер для перевода проекта на стадию устранения замечаний
// --- искусственное действие для моделирования
// +++ действие, которое должно быть произойти у пользователя

$totalCCId = 40;
$typeOfObjectId = 1;


// --------------------------------------------------------------------------------------
//  1 все назначенные эксперты совершили действие на разделе "Закончить работу с разделом"
// --------------------------------------------------------------------------------------

// --- получаем id разделов

$typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);
$docCommentTable = $typeOfObjectTableLocator->getDocsComment();
$docSectionTable = $typeOfObjectTableLocator->getDocsSection();
$assignedExpertSectionTable = $typeOfObjectTableLocator->getAssignedExpertsSection();

/*

$sectionIds = total_cc::getSectionIdsById($totalCCId, 1);
vd($sectionIds);



foreach ($sectionIds as $sectionId) {

    // --- получаем id назначенных на раздел экспертов
    $assignedExpertIds = $assignedExpertSectionTable::getExpertIdsByIdSection($sectionId);

    foreach ($assignedExpertIds as $expertId) {

//      { Действие "Закончить подготовку раздела"

        $transaction = new Transaction();

        // Перевод всех замечаний из этого раздела на стадию "Замечание подготовлено"
        $transaction->add(
            $docCommentTable,
            'updateIdStageByIdMainDocumentAndIdAuthor',
            [2, $sectionId, $expertId]
        );

        // Заканчиваем подготовку раздела
        $transaction->add(
            $assignedExpertSectionTable,
            'setSectionPreparationFinishedByIdSectionAndIdExpert',
            [$sectionId, $expertId]
        );

        // Не осталось незакончивших работу с разделом экспертов (с учетом данных в транзакции)
        if ($assignedExpertSectionTable::getCountWhereNotSectionPreparationFinishedByIdSection($sectionId) == 1) {

            // Перевод раздела на стадию "Раздел подготовлен"
            $transaction->add(
                $docSectionTable,
                'updateIdStageById',
                [2, $sectionId]
            );

            // Не осталось разделов на стадии "Подготовка раздела" (с учетом данных в транзакции)
            if ($docSectionTable::getCountByIdMainDocumentAndIdStage($totalCCId, 1) == 1) {

                // Перевод сводного замечания / заключения на стадию "Подготовка сводного замечания ПТО"
                $transaction->add(
                    '\Tables\Docs\total_cc',
                    'updateIdStageById',
                    [2, $totalCCId]
                );

                $responsible = new Responsible($totalCCId, DOCUMENT_TYPE['total_cc']);

                // Удаление предыдущих ответственных
                $responsible->deleteCurrentResponsible($transaction, false);

                // Установка отвественных сотрудников ПТО
                $responsible->createResponsibleType2($transaction, ROLE_ID['EMP_PTO']);
            }
        }

        $transactionResults = $transaction->start()->getLastResults();
        unset($transaction); // (-)
//      }
    }
}*/
// после того, как эксперт на разделе нажимает действие "Закончить работу с разделом" должен вызваться
// приватный метод проверки по переводу раздела со стадии "Подготовка раздела" на "Раздел подготовлен"

// на действии возобновить работу с разделом перевести все замечания на стадию "Подготовка замечания" одним запросом
// при добавлении замечания после выдачи не забыть ему нумерацию делать




//      { Действие пто по переводу на устранение замечаний (до этого подписания и т.д.)

    $docGroupTable = $typeOfObjectTableLocator->getDocsGroup();
    $commentInGroupTable = $typeOfObjectTableLocator->getCommentsInGroup();
    $docCommentTable = $typeOfObjectTableLocator->getDocsComment();
    $docSectionTable = $typeOfObjectTableLocator->getDocsSection();
    $assignedExpertSectionTable = $typeOfObjectTableLocator->getAssignedExpertsSection();

    $transaction = new Transaction();


    // Замечания к текущему сводному замечанию / заключению
    $comments = total_cc::getAllAssocCommentIdAndIdAttachedFileById($totalCCId, 1);

    vd($comments);
    $number = 1;

    $groups = [];

    foreach ($comments as ['id' => $id, 'id_attached_file' => $attachedFileId]) {

        // Сквозная нумерация
        $transaction->add(
            $docCommentTable,
            'updateNumberById',
            [$number++, $id]
        );

        // Формирование массива групп
        $groups[$attachedFileId ?? 'no_file'][] = $id;
    }

    vd($groups);

    $groupCount = 1;

    foreach ($groups as $attachedFileId => $commentIds) {

        $getterKey = "group_id_{$groupCount}";

        if ($attachedFileId == 'no_file') $attachedFileId = null;

        // Создание группы
        $transaction->add(
            $docGroupTable,
            'create',
            [$totalCCId, $attachedFileId],
            $getterKey
        );

        foreach ($commentIds as $commentId) {

            // Создание связи комментариев и группы
            $transaction->add(
                $commentInGroupTable,
                'create',
                [$commentId],
                null,
                $getterKey
            );
        }
        $groupCount++;
    }

    $transactionResult = $transaction->start()->getLastResults();

    vd($transactionResult);


