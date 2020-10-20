<?php

use Lib\Singles\DocumentTreeHandler;
use Lib\Singles\TemplateMaker;
use Tables\Docs\total_cc;
use Tables\Locators\TypeOfObjectTableLocator;
use Tables\FinancingSources\FinancingSourcesAggregator;


$treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');

$typeOfObjectId = $treeHandler->getTypeOfObjectId();

$typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);


$financingSourcesAggregator = new FinancingSourcesAggregator(
    FinancingSourcesAggregator::COMMON_PART_TABLE_TYPE,
    CURRENT_DOCUMENT_ID
);

TemplateMaker::registration(
    'view_financing_sources',
    TemplateMaker::HOME_WITH_DATA_VIEW . 'financing_sources.php',
    [
        'financing_sources' => $financingSourcesAggregator->getFinancingSources()
    ]
);


$docSectionTable = $typeOfObjectTableLocator->getDocsSection();
$docCommentTable = $typeOfObjectTableLocator->getDocsComment();


$sectionIds = total_cc::getSectionIdsById(CURRENT_DOCUMENT_ID, $typeOfObjectId);

$comments = $docCommentTable::getAllAssocByIdsMainDocument($sectionIds);

//todo тут подумать насчет того, чтобы из дерева что-то получить? или в дереве обрубить детей у раздела
if (!is_null($comments)) {

    // Полные и сокращенные наименования разделов
    $sectionsNames = [];

    foreach ($comments as &$comment) {

        $sectionId = $comment['id_main_document'];

        // Получение разделов один раз и только для тех, в которых есть замечания
        if (!isset($sectionsNames[$sectionId])) {

            $sectionsNames[$sectionId] = $docSectionTable::getNameAndShortNameMainBlockById($sectionId);
        }
        $comment['section_name'] = $sectionsNames[$sectionId];
    }
    unset($comment);
}
vd($comments);