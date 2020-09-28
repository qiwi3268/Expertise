<?php


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use Lib\Singles\VariableTransfer;
use Lib\AccessToDocument\AccessToDocumentTree;
use Lib\AccessToDocument\Factory;
use Lib\Singles\Helpers\PageAddress;
use Classes\DocumentTreeHandler;


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
 * @param string $label название документа
 * @param string $ref ссылка для перехода на документ
 * @param array $description индексный массив с описаниями к документу
 * @param int $depth глубина вложенности документа
 */
$addDocumentToArray = function (
    string $label,
    string $ref,
    array $description,
    int $depth
) use (&$availableDocumentsTV): void {

    $availableDocumentsTV[] = [
        'label' => $label,
        'ref' => $ref,
        'descriptions' => $description,
        'depth' => $depth
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


// Проверка заявления ------------------------------------------------------------------------
if ($treeHandler->ce_application()) {

    if ($isDocumentChecked(DOCUMENT_TYPE['application'], $treeHandler->getApplicationId())
        || $checkAccessFromApplication
    ) {

        $addDocumentToArray(
            'Заявление',
            PageAddress::createCardRef($treeHandler->getApplicationId(), 'application', 'view'),
            ['стадия', 'еще что-то'],
            0
        );

        // Проверка сводного замечания / заключения ------------------------------------------
        if ($treeHandler->ce_totalCC()) {

            if (
                $isDocumentChecked(DOCUMENT_TYPE['total_cc'], $treeHandler->getTotalCCId())
                || $checkAccessFromTotalCC()
            ) {

                $addDocumentToArray(
                    'Сводное замечание',
                    PageAddress::createCardRef($treeHandler->getTotalCCId(), 'total_cc', 'view'),
                    ['стадия', 'еще что-то'],
                    1
                );

                // Проверка разделов ---------------------------------------------------------
                if ($treeHandler->ce_sections()) {

                    // dt - document type
                    $dt = $treeHandler->getTypeOfObjectId() == 1 ? 'section_documentation_1' : 'section_documentation_2';

                    // Флаг того, что отсутствует раздел, который был проверен в route callback
                    // Этим экономим вызовы замыкания $isDocumentChecked, поскольку одновременно только
                    // один раздел мог быть проверен
                    $checkedAbsent = true;

                    foreach ($treeHandler->getSections() as $section) {

                        if ($checkedAbsent && $isDocumentChecked(DOCUMENT_TYPE[$dt], $section['id'])) {

                            $checkedAbsent = false;
                        } elseif (!$checkAccessFromSection($dt, $section['id'])) {

                            continue;
                        }

                        $addDocumentToArray(
                            'Раздел',
                            PageAddress::createCardRef($section['id'], 'section_documentation_1', 'view'),
                            ['стадия раздела', 'еще что-то'],
                            2
                        );
                    }
                }
            }
        }
    }
}

$VT->setValue('availableDocuments', $availableDocumentsTV);
