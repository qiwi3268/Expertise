<?php


namespace Classes\Application\Files\Initialization;

use Lib\Exceptions\File as FileEx;
use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\NodeStructure as NodeStructureEx;

use Lib\Singles\NodeStructure;
use Tables\Locators\TypeOfObjectTableLocator;
use Lib\Singles\Helpers\FileHandler;


/**
 * Класс-обертка для работы с документацией заявления
 *
 * Паттерн: <i>Facade</i>
 *
 */
class DocumentationFilesFacade
{

    private int $mapping_level_1;
    private int $mapping_level_2;

    /**
     * Объект инициализации файлов в заявлении
     *
     */
    private DocumentationFilesInitializer $filesInitializer;

    /**
     * Объект локатора таблиц по виду объекта
     *
     */
    private TypeOfObjectTableLocator $typeOfObjectTableLocator;

    /**
     * Структура вложенности узлов документации
     *
     */
    private NodeStructure $nodeStructure;


    /**
     * Конструктор класса
     *
     * @param int $applicationId id заявления
     * @param int $typeOfObjectId id вида объекта
     * @throws FileEx
     * @throws TablesEx
     * @throws NodeStructureEx
     */
    public function __construct(int $applicationId, int $typeOfObjectId)
    {
        $this->filesInitializer = new DocumentationFilesInitializer($applicationId, $typeOfObjectId);

        list(
            1 => $this->mapping_level_1,
            2 => $this->mapping_level_2
            ) = $this->filesInitializer->getFirstFilesMappings();

        $this->typeOfObjectTableLocator = new TypeOfObjectTableLocator($typeOfObjectId);
        $this->nodeStructure = new NodeStructure(call_user_func([$this->typeOfObjectTableLocator->getStructures(), 'getAllAssocWhereActive']));
    }


    /**
     * Предназначен для получения установленных маппингов первого и второго уровня
     *
     * @uses \Classes\Application\Files\Initialization\DocumentationFilesInitializer::getFirstFilesMappings()
     * @return array
     */
    public function getMappingsLevel(): array
    {
        return $this->filesInitializer->getFirstFilesMappings();
    }


    /**
     * Предназначен для получения объекта локатора таблиц по виду объекта
     *
     * @return TypeOfObjectTableLocator
     */
    public function getTypeOfObjectTableLocator(): TypeOfObjectTableLocator
    {
        return $this->typeOfObjectTableLocator;
    }


    /**
     * Предназначен для получения объекта построения стрктуры вложенности узлов
     *
     * @return NodeStructure
     */
    public function getNodeStructure(): NodeStructure
    {
        return $this->nodeStructure;
    }


    /**
     * Предназначен для получения нужных файлов и подписей к ним
     *
     * @uses \Classes\Application\Files\Initialization\DocumentationFilesInitializer::getNeedsFilesWithSigns()
     * @return array индексный массив с ассоциативными массивами файлов  нужных маппингов
     * @throws FileEx
     */
    public function getNeedsFilesWithSigns(): array
    {
        return $this->filesInitializer->getNeedsFilesWithSigns()[$this->mapping_level_1][$this->mapping_level_2] ?? [];
    }


    /**
     * Предназначен для получения файлов внутри структуры
     *
     * @uses \Classes\Application\Files\Initialization\DocumentationFilesInitializer::getFilesInDepthStructure()
     * @param array|null $files
     * @return array
     * @throws FileEx
     */
    public function getFilesInDepthStructure(?array $files = null): array
    {
        if (is_null($files)) {
            $files = $this->getNeedsFilesWithSigns();
        }
        return $this->filesInitializer::getFilesInDepthStructure($files, $this->nodeStructure);
    }


    /**
     * Предназначен для обработки файлов
     *
     * - устанавливает файловую иконку
     * - устанавливает результат валидации подиси
     * - устанавливает человекопонятный размер файла
     *
     * @uses \Lib\Singles\Helpers\FileHandler::setFileIconClass()
     * @uses \Lib\Singles\Helpers\FileHandler::setValidateResultJSON()
     * @uses \Lib\Singles\Helpers\FileHandler::setHumanFileSize()
     * @param array $files <i>ссылка</i> на индексный массив с ассоциативными массивами файлов
     */
    static public function handleFiles(array &$files): void
    {
        FileHandler::setFileIconClass($files);
        FileHandler::setValidateResultJSON($files);
        FileHandler::setHumanFileSize($files);
    }


    /**
     * Предназначен для обработки файлов в структуре
     *
     * @uses \Classes\Application\Files\Initialization\DocumentationFilesFacade::handleFiles()
     * @param array $filesInStructure <i>ссылка</i> на массив с файлами в структуре
     */
    static public function handleFilesInStructure(array &$filesInStructure): void
    {
        foreach ($filesInStructure as &$node) {

            if (isset($node['files'])) {

                self::handleFiles($node['files']);
            }
        }
        unset($node);
    }
}