<?php


namespace Classes\Application\Files\Initialization;

use Lib\Exceptions\File as FileEx;
use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Initialization\Initializer as MainInitializer;


/**
 * Предназначен для инициализации сохраненних файлов к типу документа <i>Заявление</i>
 *
 */
class Initializer extends MainInitializer
{

    private int $applicationId;


    /**
     * Конструктор класса
     *
     * @param RequiredMappingsSetter $filesRequiredMappings объект класса с установленными ранее нужными маппингами
     * @param int $applicationId id заявления
     * @throws FileEx
     */
    public function __construct(RequiredMappingsSetter $filesRequiredMappings, int $applicationId)
    {
        parent::__construct($filesRequiredMappings);

        $this->applicationId = $applicationId;
    }


    /**
     * Реализация абстрактного метода
     *
     * @return int
     */
    protected function getMainDocumentId(): int
    {
        return $this->applicationId;
    }
}