<?php


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use Lib\Singles\VariableTransfer;
use Lib\AccessToDocument\AccessToDocumentTree;
use Lib\AccessToDocument\Factory;
use Lib\Singles\Helpers\PageAddress;
use Classes\DocumentTreeHandler;


$VT = VariableTransfer::getInstance();


$checkedDocuments = AccessToDocumentTree::getCheckedDocuments();

$isDocumentChecked = function(string $documentType, int $documentId) use ($checkedDocuments): bool {
    foreach ($checkedDocuments as ['doc' => $doc, 'id' => $id]) {
        if ($documentType == $doc && $documentId == $id) {
            return true;
        }
    }
    return false;
};

$availableDocumentsTV = [];

$addDocumentToArray = function (
    string $label,
    string $ref,
    array  $description,
    int    $depth
) use (&$availableDocumentsTV): void {

    $availableDocumentsTV[] = [
        'label'        => $label,
        'ref'          => $ref,
        'descriptions' => $description,
        'depth'        => $depth
    ];
};

$treeHandler = new DocumentTreeHandler($VT->getValue('hierarchyTree'));

$factory = new Factory();

// Проверка заявления
if ($treeHandler->ce_application()) {

    if (!$isDocumentChecked(DOCUMENT_TYPE['application'], $treeHandler->getApplicationId())) {

        $objectApplication = $factory->getObject(DOCUMENT_TYPE['application'], [
            $treeHandler->getApplicationId(),
            $treeHandler->ce_totalCC() ? $treeHandler->getTotalCCId() : null
        ]);

        $objectApplication->checkAccess();
    }

    $addDocumentToArray(
        'Заявление',
        PageAddress::createCardRef($treeHandler->getApplicationId(), 'application', 'view'),
        ['стадия', 'еще что-то'],
        0
    );
}

// Проверка сводного замечания / заключения
if ($treeHandler->ce_totalCC()) {

    if (!$isDocumentChecked(DOCUMENT_TYPE['total_cc'], $treeHandler->getTotalCCId())) {

        $objectTotalCC = $factory->getObject(DOCUMENT_TYPE['total_cc'], [$treeHandler->getTotalCCId()]);

        try {
            $objectTotalCC->checkAccess();

            $addDocumentToArray(
                'Сводное замечание',
                PageAddress::createCardRef($treeHandler->getTotalCCId(), 'total_cc', 'view'),
                ['стадия', 'еще что-то'],
                1
            );

            // Проверка разделов
            if ($treeHandler->ce_sections()) {

                // dt - document type
                $dt = $treeHandler->getTypeOfObjectId() == 1 ? 'section_documentation_1' : 'section_documentation_2';

                // Флаг того, что отсутствует раздел, который был проверен в route callback
                // Этим экономим вызовы замыкания $isDocumentChecked, поскольку одновременно только
                // один раздел мог быть проверен
                $checkedAbsent = true;

                foreach ($treeHandler->getSections() as $section) {

                    if (!$checkedAbsent || !$isDocumentChecked(DOCUMENT_TYPE[$dt], $section['id'])) {

                        $objectSection = $factory->getObject(DOCUMENT_TYPE[$dt], [
                            $section['id'],
                            $treeHandler->getTypeOfObjectId()
                        ]);

                        try {

                            $objectSection->checkAccess();

                            $addDocumentToArray(
                                'Раздел',
                                PageAddress::createCardRef($section['id'], 'section_documentation_1', 'view'),
                                ['стадия раздела', 'еще что-то'],
                                2
                            );

                        } catch (AccessToDocumentEx $e) {
                        }
                    } else {

                        $checkedAbsent = false;

                        $addDocumentToArray(
                            'Раздел',
                            PageAddress::createCardRef($section['id'], 'section_documentation_1', 'view'),
                            ['стадия раздела', 'еще что-то'],
                            2
                        );
                    }
                }
            }
        } catch (AccessToDocumentEx $e) {
        }
    } else {

        $addDocumentToArray(
            'Сводное замечание',
            PageAddress::createCardRef($treeHandler->getTotalCCId(), 'total_cc', 'view'),
            ['стадия', 'еще что-то'],
            1
        );
    }
}

$VT->setValue('availableDocuments', $availableDocumentsTV);
