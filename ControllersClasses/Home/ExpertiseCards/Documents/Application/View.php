<?php


namespace ControllersClasses\Home\ExpertiseCards\Documents\Application;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\NodeStructure as NodeStructureEx;
use Lib\Exceptions\File as FileEx;
use Tables\Exceptions\Tables as TablesEx;
use Exception;

use ControllersClasses\Controller;
use Classes\Application\Files\Initialization\DocumentationFilesFacade;
use Classes\Application\Files\Initialization\FormFilesInitializer;
use Tables\Docs\application;


class View extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws FileEx
     * @throws NodeStructureEx
     * @throws TablesEx
     * @throws Exception
     */
    public function doExecute(): void
    {
        $applicationAssoc = application::getAssocById(CURRENT_DOCUMENT_ID);

        // Преобразование дат к строкам
        updateDatesTimestampToDdMmYyyy(
            $applicationAssoc,
            'date_planning_documentation_approval',
            'date_GPZU',
            'date_finish_building'
        );

        // Заполнение сохраненных в заявлении данных (не включая файлы)
        foreach ($applicationAssoc as $property => $value) {

            if (is_null($value)) {
                $this->VT->setExistenceFlag($property, false);
                continue;
            }

            $this->VT->setExistenceFlag($property, true);
            $this->VT->setValue($property, $value);
        }

        // Сохраненные файлы анкеты (не включая документацию)
        $formFilesInitializer = new FormFilesInitializer(CURRENT_DOCUMENT_ID);

        $needsFiles = $formFilesInitializer->getNeedsFilesWithSigns();

        // Обработка файловых массивов
        foreach ($needsFiles as &$mapping_level_2) {
            foreach ($mapping_level_2 as &$files) {
                if (!is_null($files)) {
                    DocumentationFilesFacade::handleFiles($files);
                }
            }
            unset($files);
        }
        unset($mapping_level_2);

        $this->VT->setValue('form_files', $needsFiles);

        // Сохранен вид объекта, показываем документацию
        if ($this->VT->getExistenceFlag('type_of_object')) {

            $documentationFilesFacade = new DocumentationFilesFacade(CURRENT_DOCUMENT_ID, $this->VT->getValue('type_of_object')['id']);

            $mappings = $documentationFilesFacade->getMappingsLevel();

            // Устанавливаем маппинги для работы js по скачиванию файлов
            $this->VT->setValue('documentation_mapping_level_1', $mappings['1']);
            $this->VT->setValue('documentation_mapping_level_2', $mappings['2']);

            $filesInStructure = $documentationFilesFacade->getFilesInDepthStructure();
            DocumentationFilesFacade::handleFilesInStructure($filesInStructure);

            $this->VT->setValue('documentation_files_in_structure', $filesInStructure);
        }
    }
}