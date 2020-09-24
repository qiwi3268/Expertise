<?php


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;

use Lib\Singles\VariableTransfer;
use Lib\AccessToDocument\AccessToDocumentTree;
use Classes\DocumentTreeHandler;
use Lib\AccessToDocument\Factory;


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
        '/home/application/view?id_document=' . $treeHandler->getApplicationId(),
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
                '/home/application/view?id_document=' . $treeHandler->getTotalCCId(),
                ['стадия', 'еще что-то'],
                1
            );

            // Проверка разделов
            if ($treeHandler->ce_sections()) {

                // Флаг того, что отсутствует раздел, который был проверен в route callback
                // Этим экономим вызовы замыкания $isDocumentChecked, поскольку одновременно только
                // один раздел мог быть проверен
                $checkedAbsent = true;

                foreach ($treeHandler->getSections() as $section) {

                    if (!$checkedAbsent || !$isDocumentChecked(DOCUMENT_TYPE['section'], $section['id'])) {

                        $objectSection = $factory->getObject(DOCUMENT_TYPE['section'], [
                            $section['id'],
                            $treeHandler->getTypeOfObjectId()
                        ]);

                        try {

                            $objectSection->checkAccess();

                            $addDocumentToArray(
                                'Раздел',
                                '/home/application/view?id_document=' . $section['id'],
                                ['стадия раздела', 'еще что-то'],
                                2
                            );

                        } catch (AccessToDocumentEx $e) {
                        }
                    } else {

                        $checkedAbsent = false;

                        $addDocumentToArray(
                            'Раздел',
                            '/home/application/view?id_document=' . $section['id'],
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
            '/home/application/view?id_document=' . $treeHandler->getTotalCCId(),
            ['стадия', 'еще что-то'],
            1
        );
    }
}

$VT->setValue('availableDocuments', $availableDocumentsTV);
