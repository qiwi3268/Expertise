<?php


namespace PageControllers\Home\ExpertiseCards\Sidebars;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;
use functions\Exceptions\Functions as FunctionsEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use core\Classes\ControllersInterface\PageController;
use Lib\AccessToDocument\Factory;
use Lib\AccessToDocument\AccessToDocumentTree;
use Lib\Singles\DocumentTreeHandler;
use Lib\Singles\Helpers\PageAddress;
use Tables\Locators\DocumentTypeTableLocator;
use Tables\Locators\TypeOfObjectTableLocator;
use Tables\AssignedExperts\total_cc as assigned_expert_total_cc;


class Hierarchy extends PageController
{

    /**
     * Массив документов, к которым был проверен доступ на этапе route callbacks
     *
     */
    private array $checkedDocuments;

    /**
     * Обработчик дерева документов
     *
     */
    private DocumentTreeHandler $treeHandler;

    /**
     * Фабрика получения объектов проверки доступа к документам
     *
     */
    private Factory $factory;

    /**
     * Результирующий массив для view
     *
     */
    private array $availableDocumentsTV;


    /**
     * Конструктор класса
     *
     * @throws DocumentTreeHandlerEx
     */
    public function __construct()
    {
        parent::__construct();

        $this->checkedDocuments = AccessToDocumentTree::getCheckedDocuments();
        $this->treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');
        $this->factory = new Factory();
    }

    /**
     * Реализация абстрактного метода
     *
     * @throws AccessToDocumentEx
     * @throws DataBaseEx
     * @throws ReflectionException
     * @throws FunctionsEx
     * @throws TablesEx
     */
    public function doExecute(): void
    {
        // Проверка заявления ------------------------------------------------------------------------
        if ($this->treeHandler->ce_application()) {

            if (
                $this->isDocumentChecked(DOCUMENT_TYPE['application'], $this->treeHandler->getApplicationId())
                || $this->checkAccessFromApplication()
            ) {

                $applicationTableLocator = new DocumentTypeTableLocator(DOCUMENT_TYPE['application']);

                $docApplicationTable = $applicationTableLocator->getDocs();

                $this->addDocumentToArray(
                    'application',
                    'Заявление',
                    PageAddress::createCardRef($this->treeHandler->getApplicationId(), 'application', 'view'),
                    $docApplicationTable::getNameStageById($this->treeHandler->getApplicationId()),
                    CURRENT_DOCUMENT_TYPE == DOCUMENT_TYPE['application']
                );

                // Проверка сводного замечания / заключения ------------------------------------------
                if ($this->treeHandler->ce_totalCC()) {

                    $totalCCId = $this->treeHandler->getTotalCCId();

                    $typeOfObjectTableLocator = new TypeOfObjectTableLocator($this->treeHandler->getTypeOfObjectId());

                    if (
                        $this->isDocumentChecked(DOCUMENT_TYPE['total_cc'], $totalCCId)
                        || $this->checkAccessFromTotalCC()
                    ) {

                        $totalCCTableLocator = new DocumentTypeTableLocator(DOCUMENT_TYPE['total_cc']);

                        $docTotalCCTable = $totalCCTableLocator->getDocs();

                        $leadExpert = getFIO(assigned_expert_total_cc::getAssocExpertWhereLeadByIdTotalCC($totalCCId));

                        $commonPartExperts = [];

                        foreach (assigned_expert_total_cc::getAllAssocExpertWhereCommonPartByIdTotalCC($totalCCId) as $expert) {
                            $commonPartExperts[] = getFIO($expert);
                        }

                        $this->addDocumentToArray(
                            'total_cc',
                            'Сводное замечание',
                            PageAddress::createCardRef($totalCCId, 'total_cc', 'view'),
                            $docTotalCCTable::getNameStageById($totalCCId),
                            CURRENT_DOCUMENT_TYPE == DOCUMENT_TYPE['total_cc'],
                            [
                                'leadExpert'        => $leadExpert,
                                'commonPartExperts' => $commonPartExperts
                            ]
                        );

                        // Проверка разделов ---------------------------------------------------------
                        if ($this->treeHandler->ce_sections()) {

                            // dt - document type
                            $dt = $this->treeHandler->getTypeOfObjectId() == 1 ? DOCUMENT_TYPE['section_documentation_1'] : DOCUMENT_TYPE['section_documentation_2'];

                            $sectionTableLocator = new DocumentTypeTableLocator($dt);

                            // Таблица документа раздела
                            $docSectionTable = $sectionTableLocator->getDocs();

                            // Таблица назначенных на раздел экспертов
                            $assignedExpertTable = $typeOfObjectTableLocator->getAssignedExpertsSection();

                            // Флаг того, что отсутствует раздел, который был проверен в route callback
                            // Этим экономим вызовы замыкания $isDocumentChecked, поскольку одновременно только
                            // один раздел мог быть проверен
                            $checkedAbsent = true;

                            foreach ($this->treeHandler->getSections() as $section) {

                                if ($checkedAbsent && $this->isDocumentChecked($dt, $section['id'])) {

                                    $checkedAbsent = false;
                                } elseif (!$this->checkAccessFromSection($dt, $section['id'])) {

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

                                $this->addDocumentToArray(
                                    'sections',
                                    "Раздел {$short_name}",
                                    PageAddress::createCardRef($section['id'], 'section_documentation_1', 'view'),
                                    $docSectionTable::getNameStageById($section['id']),
                                    CURRENT_DOCUMENT_TYPE == $dt && CURRENT_DOCUMENT_ID == $section['id'],
                                    [
                                        'tooltipName'     => $name,
                                        'assignedExperts' => $FIOs
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }
        $this->VT->setValue('available_documents', $this->availableDocumentsTV);
    }


    /**
     * Предназначен для проверки того, был ли документ проверен на этапе route callbacks
     *
     * @param string $documentType тип документа
     * @param int $documentId id документа
     * @return bool
     */
    private function isDocumentChecked(string $documentType, int $documentId): bool
    {
        foreach ($this->checkedDocuments as ['doc' => $doc, 'id' => $id]) {
            if ($documentType == $doc && $documentId == $id) {
                return true;
            }
        }
        return false;
    }


    /**
     * Предназначен для добавления документа к массиву, который будет отрисовываться во view
     *
     * @param string $type тип документа, в который будет вноситься информация
     * @param string $label название документа
     * @param string $ref ссылка для перехода на документ
     * @param string $stage стадия документа
     * @param bool $isSelected выбран ли текущий элемент
     * @param array|null $info ассоциативный массив с информационными данными
     */
    private function addDocumentToArray(
        string $type,
        string $label,
        string $ref,
        string $stage,
        bool $isSelected,
        ?array $info = null
    ): void {

        $this->availableDocumentsTV[$type][] = [
            'type'       => $type,
            'label'      => $label,
            'ref'        => $ref,
            'stage'      => $stage,
            'isSelected' => $isSelected,
            'info'       => $info
        ];
    }


    /**
     * Предназначен для проверки доступа пользователя к заявлению
     *
     * @return bool
     * @throws AccessToDocumentEx
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    private function checkAccessFromApplication(): bool
    {
        $objectApplication = $this->factory->getObject(DOCUMENT_TYPE['application'],
            [
                $this->treeHandler->getApplicationId(),
                $this->treeHandler->ce_totalCC() ? $this->treeHandler->getTotalCCId() : null
            ]
        );

        try {
            $objectApplication->checkAccess();
            return true;
        } catch (AccessToDocumentEx $e) {
            return false;
        }
    }


    /**
     * Предназначен для проверки доступа пользователя к сводному замечанию / заключению
     *
     * @return bool
     * @throws AccessToDocumentEx
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    private function checkAccessFromTotalCC(): bool
    {
        $objectTotalCC = $this->factory->getObject(
            DOCUMENT_TYPE['total_cc'],
            [$this->treeHandler->getTotalCCId()]
        );

        try {
            $objectTotalCC->checkAccess();
            return true;
        } catch (AccessToDocumentEx $e) {
            return false;
        }
    }

    /**
     * Предназначен для проверки доступа пользователя к разделу
     *
     * @param string $documentType тип документа
     * @param int $sectionId id раздела
     * @return bool
     * @throws AccessToDocumentEx
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    private function checkAccessFromSection(string $documentType, int $sectionId): bool
    {
        $objectSection = $this->factory->getObject(
            DOCUMENT_TYPE[$documentType],
            [
                $sectionId,
                $this->treeHandler->getTypeOfObjectId()
            ]
        );

        try {
            $objectSection->checkAccess();
            return true;
        } catch (AccessToDocumentEx $e) {
            return false;
        }
    }
}