<?php

use Lib\Singles\DocumentTreeHandler;
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
$sectionIds = total_cc::getSectionIdsById(38, 1);
vd($sectionIds);

foreach ($sectionIds as $sectionId) {

    // --- получаем id назначенных эеспертов
    $assignedExpertIds = assigned_expert_section_documentation_1::getExpertIdsByIdSection($sectionId);

    foreach ($assignedExpertIds as $expertId) {

        // +++ Завершаем работу с разделом (Действие "Закончить подготовку раздела")
        assigned_expert_section_documentation_1::setSectionPreparationFinishedByIdSectionAndIdExpert($sectionId, $expertId);

        // +++ Перевод всех замечаний на стадию "Замечание подготовлено"
        comment_documentation_1::updateIdStageByIdMainDocument(2, $sectionId);

        // +++ Остались ли незакончившие работу с этим разделом эксперты
        if (is_null(assigned_expert_section_documentation_1::getExpertsIdsWhereNotSectionPreparationFinishedByIdSection($sectionId))) {

            // Перевод раздела на стадию "Раздел подготовлен"
            section_documentation_1::updateIdStageById(2, $sectionId);
        }
    }
}


// после того, как эксперт на разделе нажимает действие "Закончить работу с разделом" должен вызваться
// приватный метод проверки по переводу раздела со стадии "Подготовка раздела" на "Раздел подготовлен"

// на действии возобновить работу с разделом перевести все замечания на стадию "Подготовка замечания" (одним запросом, а не в цикле)
