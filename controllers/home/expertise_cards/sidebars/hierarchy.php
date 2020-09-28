<?php


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use Lib\Singles\VariableTransfer;
use Lib\AccessToDocument\AccessToDocumentTree;
use Lib\AccessToDocument\Factory;
use Lib\Singles\Helpers\PageAddress;
use Classes\DocumentTreeHandler;
use Tables\Docs\TableLocator;
use Tables\DocumentationTypeTableLocator;


$VT = VariableTransfer::getInstance();


$checkedDocuments = AccessToDocumentTree::getCheckedDocuments();


/**
 * Предназначен для проверки того, был ли документ проверен на этапе route controllers
 *
 * @param string $documentType тип документа
 * @param int $documentId id документа
 * @return bool
 */
$isDocumentChecked = function (
    string $documentType,
    int $documentId
) use ($checkedDocuments): bool {
    foreach ($checkedDocuments as ['doc' => $doc, 'id' => $id]) {
        if ($documentType == $doc && $documentId == $id) {
            return true;
        }
    }
    return false;
};


$availableDocumentsTV = [];


/**
 * Предназначен для добавления документа к массиву, который будет отрисовываться во view
 *
 * @param string $type тип документа, в который будет вноситься информация
 * @param string $label название документа
 * @param string $ref ссылка для перехода на документ
 * @param string $stage стадия документа
 * @param bool $isSelected выбран ли текущий элемент
 * @param array $description индексный массив с описаниями к документу
 * @param string|null $tooltip текст для всплывающей подсказки
 */
$addDocumentToArray = function (
    string $type,
    string $label,
    string $ref,
    string $stage,
    bool $isSelected,
    array $description = [],
    ?string $tooltip = null
) use (&$availableDocumentsTV): void {

    $availableDocumentsTV[$type][] = [
        'type'         => $type,
        'label'        => $label,
        'ref'          => $ref,
        'stage'        => $stage,
        'isSelected'   => $isSelected,
        'descriptions' => $description,
        'tooltip'      => $tooltip
    ];
};

// hierarchyTree определен ранее в AccessToDocumentTreeChecker
$treeHandler = new DocumentTreeHandler($VT->getValue('hierarchyTree'));

$factory = new Factory();


/**
 * Предназначен для проверки доступа пользователя к заявлению
 *
 * @return bool
 */
$checkAccessFromApplication = function () use ($treeHandler, $factory): bool {

    $objectApplication = $factory->getObject(DOCUMENT_TYPE['application'], [
        $treeHandler->getApplicationId(),
        $treeHandler->ce_totalCC() ? $treeHandler->getTotalCCId() : null
    ]);

    try {
        $objectApplication->checkAccess();
        return true;
    } catch (AccessToDocumentEx $e) {
        return false;
    }
};


/**
 * Предназначен для проверки доступа пользователя к сводному замечанию / заключению
 *
 * @return bool
 */
$checkAccessFromTotalCC = function () use ($treeHandler, $factory): bool {

    $objectTotalCC = $factory->getObject(DOCUMENT_TYPE['total_cc'], [$treeHandler->getTotalCCId()]);

    try {
        $objectTotalCC->checkAccess();
        return true;
    } catch (AccessToDocumentEx $e) {
        return false;
    }
};


/**
 * Предназначен для проверки доступа пользователя к разделу
 *
 * @param string $documentType тип документа
 * @param int $sectionId id раздела
 * @return bool
 */
$checkAccessFromSection = function (
    string $documentType,
    int $sectionId
) use ($treeHandler, $factory): bool {

    $objectSection = $factory->getObject(DOCUMENT_TYPE[$documentType], [
        $sectionId,
        $treeHandler->getTypeOfObjectId()
    ]);

    try {
        $objectSection->checkAccess();
        return true;
    } catch (AccessToDocumentEx $e) {
        return false;
    }
};

$docsTableLocator = new TableLocator();

// Проверка заявления ------------------------------------------------------------------------
if ($treeHandler->ce_application()) {

    if ($isDocumentChecked(DOCUMENT_TYPE['application'], $treeHandler->getApplicationId())
        || $checkAccessFromApplication
    ) {

        $docApplicationTable = $docsTableLocator->getDocTableByDocumentType(DOCUMENT_TYPE['application']);

        $addDocumentToArray(
            'application',
            'Заявление',
            PageAddress::createCardRef($treeHandler->getApplicationId(), 'application', 'view'),
            $docApplicationTable::getNameStageById($treeHandler->getApplicationId()),
            CURRENT_DOCUMENT_TYPE == DOCUMENT_TYPE['application']
        );

        // Проверка сводного замечания / заключения ------------------------------------------
        if ($treeHandler->ce_totalCC()) {

            $documentationTypeTableLocator = new DocumentationTypeTableLocator($treeHandler->getTypeOfObjectId());

            if (
                $isDocumentChecked(DOCUMENT_TYPE['total_cc'], $treeHandler->getTotalCCId())
                || $checkAccessFromTotalCC()
            ) {

                $docTotalCCTable = $docsTableLocator->getDocTableByDocumentType(DOCUMENT_TYPE['total_cc']);

                $addDocumentToArray(
                    'total_cc',
                    'Сводное замечание',
                    PageAddress::createCardRef($treeHandler->getTotalCCId(), 'total_cc', 'view'),
                    $docTotalCCTable::getNameStageById($treeHandler->getTotalCCId()),
                    CURRENT_DOCUMENT_TYPE == DOCUMENT_TYPE['total_cc']
                );

                // Проверка разделов ---------------------------------------------------------
                if ($treeHandler->ce_sections()) {

                    // dt - document type
                    $dt = $treeHandler->getTypeOfObjectId() == 1 ? DOCUMENT_TYPE['section_documentation_1'] : DOCUMENT_TYPE['section_documentation_2'];

                    // Таблица документа раздела
                    $docSectionTable = $docsTableLocator->getDocTableByDocumentType($dt);
                    // Таблица назначенных на раздел экспертов
                    $assignedExpertTable = $documentationTypeTableLocator->getAssignedExpertSection();

                    // Флаг того, что отсутствует раздел, который был проверен в route callback
                    // Этим экономим вызовы замыкания $isDocumentChecked, поскольку одновременно только
                    // один раздел мог быть проверен
                    $checkedAbsent = true;

                    foreach ($treeHandler->getSections() as $section) {

                        if ($checkedAbsent && $isDocumentChecked($dt, $section['id'])) {

                            $checkedAbsent = false;
                        } elseif (!$checkAccessFromSection($dt, $section['id'])) {

                            continue;
                        }

                        // ФИО назначенных экспертов
                        $FIOs = [];

                        foreach ($assignedExpertTable::getAllAssocFIOByIdSection($section['id']) as $FIO) {
                            $FIOs[] = getFIO($FIO, true);
                        }

                        list(
                            'name'       => $name,
                            'short_name' => $short_name
                            ) = $docSectionTable::getNameAndShortNameMainBlockById($section['id']);

                        $addDocumentToArray(
                            'sections',
                            "Раздел {$short_name}",
                            PageAddress::createCardRef($section['id'], 'section_documentation_1', 'view'),
                            $docSectionTable::getNameStageById($section['id']),
                            CURRENT_DOCUMENT_TYPE == $dt && CURRENT_DOCUMENT_ID == $section['id'],
                            $FIOs,
                            $name
                        );
                    }
                }
            }
        }
    }
}
$VT->setValue('availableDocuments', $availableDocumentsTV);