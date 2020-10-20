<?php


use Lib\Singles\DocumentTreeHandler;
use Tables\Docs\total_cc;
use Tables\assigned_expert_section_documentation_1;

$totalCCId = 38;
$typeOfObjectId = 1;

// Временный контроллер для перевода проекта на стадию устранения замечаний


// - 1 все назначенные эксперты совершили действие на разделе "Закончить работу с разделом"
// - получаем id замечаний
$sectionIds = total_cc::getSectionIdsById(27, 1);
vd($sectionIds);

foreach ($sectionIds as $sectionId) {
    $expertIds = assigned_expert_section_documentation_1::getExpertIdsByIdSection($sectionId);
    vd($expertIds);
}



// после того, как эксперт на разделе нажимает действие "Закончить работу с разделом" должен вызваться
// приватный метод проверки по переводу раздела со стадии "Подготовка раздела" на "Раздел подготовлен"
