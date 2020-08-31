<?php


namespace Classes\Application\Files\Initialization;


// Класс для инициализации сохраненных файлов
//
class Initializator extends \Lib\Files\Initialization\Initializator{
    
    private int $applicationId; // id главного документа (заявления)
    
    
    // Принимает параметры-----------------------------------
    // filesRequiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // applicationId : id заявления
    //
    public function __construct(\Lib\Files\Mappings\RequiredMappingsSetter $filesRequiredMappings, int $applicationId){
        
        parent::__construct($filesRequiredMappings);
        
        $this->applicationId = $applicationId;
    }
    
    
    protected function getMainDocumentId():int {
        
        return $this->applicationId;
    }
}