<?php


namespace Classes\Application\Files\Initialization;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;

use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Initialization\Initializer as MainInitializer;


/**
 * Предназначен для инициализации сохраненних файлов анкеты заявления
 *
 */
class FormFilesInitializer extends MainInitializer
{

    private int $applicationId;


    /**
     * Конструктор класса
     *
     * @param int $applicationId id заявления
     * @throws FileEx
     */
    public function __construct(int $applicationId)
    {
        $requiredMappingsSetter = new RequiredMappingsSetter();
        $requiredMappingsSetter->setMappingLevel1(1);

        $this->applicationId = $applicationId;

        parent::__construct($requiredMappingsSetter);
    }


    /**
     * Реализация абстрактного метода
     *
     * @param string $fileClassName
     * @return array|null
     * @throws DataBaseEx
     */
    protected function getFiles(string $fileClassName): ?array
    {
        return $fileClassName::getAllAssocWhereNeedsByIdMainDocument($this->applicationId);
    }
}