<?php


namespace ControllersClasses\Home\ExpertiseCards\Documents\Section\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Lib\Exceptions\File as FileEx;
use Lib\Exceptions\NodeStructure as NodeStructureEx;
use Lib\Exceptions\MiscInitializer as MiscInitializerEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use Tables\Exceptions\Tables as TablesEx;

use ControllersClasses\Controller;
use Lib\Miscs\Initialization\Initializer as MiscsInitializer;
use Lib\Singles\DocumentTreeHandler;
use Classes\Application\Files\Initialization\DocumentationFilesFacade;


/**
 * Действие "Создать описательную часть"
 *
 */
class Action1 extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     * @throws DocumentTreeHandlerEx
     * @throws FileEx
     * @throws MiscInitializerEx
     * @throws NodeStructureEx
     * @throws PrimitiveValidatorEx
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function doExecute(): void
    {
        $treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');

        $applicationId = $treeHandler->getApplicationId();
        $typeOfObjectId = $treeHandler->getTypeOfObjectId();


        // Справочник "Критичность замечания"
        //
        $miscInitializer = new MiscsInitializer(['comment_criticality']);

        $this->VT->setValue('comment_criticality', $miscInitializer->getPaginationSingleMiscs()['comment_criticality']);


        // Структура документации с файлами
        //
        $documentationFilesFacade = new DocumentationFilesFacade($applicationId, $typeOfObjectId);

        $mappings = $documentationFilesFacade->getMappingsLevel();

        // Устанавливаем маппинги для работы js по скачиванию файлов
        $this->VT->setValue('documentation_mapping_level_1', $mappings['1']);
        $this->VT->setValue('documentation_mapping_level_2', $mappings['2']);

        $nodeStructure = $documentationFilesFacade->getNodeStructure();

        $filesInStructure = $documentationFilesFacade->getFilesInDepthStructure();

        $uniqueIds = [];

        // Формирование структуры документации, где есть загруженные файлы
        // и всех их родительские узлы до самого старшего
        foreach ($filesInStructure as $node) {

            if (isset($node['files'])) {

                $uniqueIds[] = $node['id'];

                foreach ($nodeStructure->getNodeParents($node['id'], $filesInStructure) as $parentId) {

                    if (!in_array($parentId, $uniqueIds)) {

                        $uniqueIds[] = $parentId;
                    }
                }
            }
        }

        // Сортировка id разделов по возрастанию, чтобы структура отображалась корректно
        sort($uniqueIds, SORT_NUMERIC);

        $filesInStructure = array_filter($filesInStructure, fn($node) => (in_array($node['id'], $uniqueIds)));

        DocumentationFilesFacade::handleFilesInStructure($filesInStructure);

        $this->VT->setValue('documentation_files_in_structure', $filesInStructure);
    }
}