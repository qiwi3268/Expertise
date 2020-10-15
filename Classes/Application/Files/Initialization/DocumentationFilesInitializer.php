<?php


namespace Classes\Application\Files\Initialization;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;

use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Initialization\Initializer as MainInitializer;


/**
 * Предназначен для инициализации сохраненних файлов документации заявления
 *
 */
class DocumentationFilesInitializer extends MainInitializer
{

    private int $applicationId;
    private int $typeOfObjectId;
    private int $mapping_level_1;
    private int $mapping_level_2;


    /**
     * Конструктор класса
     *
     * @param int $applicationId id заявления
     * @param int $typeOfObjectId id вида объекта
     * @throws FileEx
     */
    public function __construct(int $applicationId, int $typeOfObjectId)
    {
        if ($typeOfObjectId == 1) {
            $this->mapping_level_1 = 2;
            $this->mapping_level_2 = 1;
        } else {
            $this->mapping_level_1 = 2;
            $this->mapping_level_2 = 2;
        }

        $requiredMappingsSetter = new RequiredMappingsSetter();
        $requiredMappingsSetter->setMappingLevel2($this->mapping_level_1, $this->mapping_level_2);

        $this->applicationId = $applicationId;
        $this->typeOfObjectId = $typeOfObjectId;

        parent::__construct($requiredMappingsSetter);
    }


    /**
     * Реализация абстрактного метода
     *
     * @param string $fileClassName
     * @return array|null
     * @throws DataBaseEx
     */
    protected function getFiles($fileClassName): ?array
    {
        return $fileClassName::getAllAssocWhereNeedsByIdMainDocument($this->applicationId);
    }
}