<?php


use Lib\Singles\VariableTransfer;
use Lib\Singles\DocumentTreeHandler;
use Lib\Singles\StatisticDiagram;
use Classes\Section\Files\Initialization\AttachedFilesFacade;
use Tables\Locators\TypeOfObjectTableLocator;



$VT = VariableTransfer::getInstance();

$treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');

$typeOfObjectId = $treeHandler->getTypeOfObjectId();

$typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);


// Описание раздела
//
$descriptionTable = $typeOfObjectTableLocator->getDescriptivePartDescription();

$descriptionsTV = [];

if (!is_null($descriptions = $descriptionTable::getAllAssocByIdMainDocument(CURRENT_DOCUMENT_ID))) {

    foreach ($descriptions as $description) {

        $author = getFIO($description, false);

        $descriptionsTV[$author] = $description['description'];
    }
}

$VT->setValue('descriptions', $descriptionsTV);


// Технико-экономические показатели
//
$TEPTable = $typeOfObjectTableLocator->getDescriptivePartTEP();

$TEPsByAuthorsTV = [];

if (!is_null($TEPs = $TEPTable::getAllAssocByIdMainDocument(CURRENT_DOCUMENT_ID))) {

    foreach ($TEPs as $TEP) {

        $author = getFIO($TEP);

        $TEPsByAuthorsTV[$author][] = [
            'indicator' => $TEP['indicator'],
            'value'     => "{$TEP['value']} {$TEP['measure']}",
            'note'      => $TEP['note'] ?? 'Отсутствует'
        ];
    }
}

$VT->setValue('TEPs_by_authors', $TEPsByAuthorsTV);


// Замечания к разделу
//
$docCommentTable = $typeOfObjectTableLocator->getDocsComment();

$comments = $docCommentTable::getAllAssocByIdsMainDocument([CURRENT_DOCUMENT_ID]) ?? [];

//todo тут подумать насчет того, чтобы из дерева что-то получить? или в дереве обрубить детей у раздела
if (!empty($comments)) {

    $commentIds = compressArrayValuesByKey($comments, 'id');

    $attachedFilesInitializer = new AttachedFilesFacade($commentIds, $typeOfObjectId);

    $attachedFiles = $attachedFilesInitializer->getNeedsFilesWithSigns();
    AttachedFilesFacade::handleFiles($attachedFiles);

    $packedAttachedFiles = $attachedFilesInitializer->packFilesToCommentIds($attachedFiles);

    foreach ($comments as &$comment) {

        $comment['author'] = getFIO($comment);
        $comment['file'] = $packedAttachedFiles[$comment['id']];
        $comment['number'] ??= '-';
    }
    unset($comment);
}

    // Сгруппированная статистика по критичности замечаний\
//todo тут брать активные или все замечания

//todo тут в запросе сделать id стадди?

$criticalityGroups = $docCommentTable::getCommentCriticalityGroupsByIdMainDocument(CURRENT_DOCUMENT_ID);

$criticalityDiagram = new StatisticDiagram(array_sum(compressArrayValuesByKey($criticalityGroups, 'count')));



foreach ($criticalityGroups as ['name' => $label, 'count' => $count]) {
    $criticalityDiagram->addColumn($label, $count);
}

//vd($criticalityDiagram->getDiagram());
$VT->setValue('criticality_all_comments_diagram', $criticalityDiagram->getDiagram());

//vd($comments);
//todo пока комментс потом уже на разные таблицы
$VT->setValue('comments', $comments);
//если есть выборка то тогда делаем запросы на карточки
