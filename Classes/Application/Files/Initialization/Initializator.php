<?php


namespace Classes\Application\Files\Initialization;

use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Initialization\Initializator as ParentInitializator;


// Класс для инициализации сохраненных файлов к заявлению
//
class Initializator extends ParentInitializator
{

    private int $applicationId; // id главного документа (заявления)


    // Принимает параметры-----------------------------------
    // filesRequiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // applicationId : id заявления
    //
    public function __construct(RequiredMappingsSetter $filesRequiredMappings, int $applicationId)
    {
        parent::__construct($filesRequiredMappings);

        $this->applicationId = $applicationId;
    }


    protected function getMainDocumentId(): int
    {
        return $this->applicationId;
    }
}