<?php


namespace ControllersClasses\Home\ExpertiseCards\Documents\Application\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Lib\Exceptions\NodeStructure as NodeStructureEx;
use Lib\Exceptions\MiscInitializer as MiscInitializerEx;
use Lib\Exceptions\File as FileEx;
use Tables\Exceptions\Tables as TableEx;
use functions\Exceptions\Functions as FunctionsEx;
use LogicException;

use ControllersClasses\Controller;
use Lib\Singles\DocumentTreeHandler;
use Classes\Application\Files\Initialization\DocumentationFilesFacade;
use Classes\Application\Actions\Miscs\Initialization\action_2 as MiscInitializer;
use Tables\user;


/**
 * Действие "Назначить экспертов"
 *
 */
class Action2 extends Controller
{

    private DocumentationFilesFacade $documentationFilesFacade;
    private array $filesInStructure;

    /**
     * Смещение в рамках основного цикла по перебору файлов документации
     *
     */
    private int $offset = 0;


    /**
     * Конструктор класса
     *
     * @throws DocumentTreeHandlerEx
     * @throws FileEx
     * @throws NodeStructureEx
     * @throws TableEx
     */
    public function construct(): void
    {
        $treeHandler = DocumentTreeHandler::getInstanceByKey('AccessToDocumentTree');

        $this->documentationFilesFacade = new DocumentationFilesFacade(CURRENT_DOCUMENT_ID, $treeHandler->getTypeOfObjectId());

        $this->filesInStructure = array_values($this->documentationFilesFacade->getFilesInDepthStructure());
    }


    /**
     * Реализация абстрактного метода
     *
     * @throws DataBaseEx
     * @throws FunctionsEx
     * @throws MiscInitializerEx
     */
    public function doExecute(): void
    {
        // Формирование экспертов для назначения на разделы
        //
        $activeExperts = user::getActiveExperts();

        foreach ($activeExperts as &$expert) {
            $expert['fio'] = getFIO($expert);
            unset($expert['last_name'], $expert['first_name'],$expert['middle_name']);
        }
        unset($expert);

        $this->VT->setValue('experts', $activeExperts);

        $filesInStructure = $this->filesInStructure;
        $filesInStructureTV = [];

        // Отображаем только те разделы, к которым есть файлы и которые привязаны к 341 приказу

        $ids = []; // Индексный массив id подошедших блоков из бланка заключения по 341 приказу

        foreach ($filesInStructure as $index => $node) {

            // Раздел привязан к блоку из бланка заключения по 341 приказу
            if (!is_null($node['id_main_block_341'])) {

                $entryFlag = false;

                if (isset($node['files'])) {

                    $tmpSelf = $filesInStructure[$index];

                    DocumentationFilesFacade::handleFiles($tmpSelf['files']);

                    $tmpSelf['self_files'] = $tmpSelf['files'];
                    unset($tmpSelf['files']);

                    $filesInStructureTV[$index] = $tmpSelf;
                    $entryFlag = true;
                }

                if (!is_null($vorIndex = $this->hasVORWithFiles($index))) {

                    $VORFiles = $filesInStructure[$vorIndex]['files'];

                    DocumentationFilesFacade::handleFiles($VORFiles);

                    // Если есть собственные файлы - записываем туда ключ vor_files.
                    // Если файлов нет - нужно записать в массив TV этот раздел с ключом vor_files
                    if ($entryFlag) {

                        $filesInStructureTV[$index]['vor_files'] = $VORFiles;
                    } else {

                        // Если собственных файлов нет - надо добавить в массив для view исходный массив
                        $tmpSelf = $filesInStructure[$index];
                        $tmpSelf['vor_files'] = $VORFiles;
                        unset($tmpSelf['files']);

                        $filesInStructureTV[$index] = $tmpSelf;
                        $entryFlag = true;
                    }
                }
                if ($entryFlag) $ids[] = $filesInStructure[$index]['id_main_block_341'];
            }
            $this->offset++;
        }
        $this->VT->setValue('documentation_files_in_structure', $filesInStructureTV);

        // Формирование справочников разделов из 341 приказа
        //
        $miscInitializer = new MiscInitializer(
            call_user_func(
                [
                    $this->documentationFilesFacade->getTypeOfObjectTableLocator()->getOrder341MainBlock(),
                    'getAllAssocWhereIdNotInIds'
                ], $ids)
        );

        foreach ($miscInitializer->getPaginationSingleMiscs() as $miscName => $misc) {
            $this->VT->setValue($miscName, $misc);
        }
    }


    /**
     * Предназначен для получения информации - есть ли у узла дочерний узел
     * ведомостей объемов работ, у которого есть загруженные файлы
     *
     * @param int $currentIndex текущий индекс в перебираемом массиве
     * @return int|null <b>int</b> индекс элемента в исходном массиве, который
     * является дочерним узлом (к элементу currentIndex), ВОРом, в нем есть загруженные файлы<br>
     * <b>false</b> элемент не существует
     * @throws LogicException
     */
    private function hasVORWithFiles(int $currentIndex): ?int
    {
        $indexes = [];

        $filesInStructure = $this->filesInStructure;

        // ВОРы должны быть обязательно после текущего элемента массива
        for ($l = ($this->offset + 1); $l < count($filesInStructure); $l++) {

            if (
                $filesInStructure[$l]['id_parent_node'] == $filesInStructure[$currentIndex]['id']
                && $filesInStructure[$l]['is_vor']
                && isset($filesInStructure[$l]['files'])
            ) {
                $indexes[] = $l;
            }
        }

        if (($count = count($indexes)) > 1) {
            $debug = implode(', ', $indexes);
            throw new LogicException("У узла с id: {$filesInStructure[$currentIndex]['id']} найдено: {$count} дочерних узлов ВОРов с id: '{$debug}'");
        }
        return $count == 1 ? $indexes[0] : null;
    }

}