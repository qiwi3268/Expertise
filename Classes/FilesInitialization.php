<?php


// Класс инициализации сохраненных файлов в рамках заявления
//
class FilesInitialization{
    
    // Массив нужных маппингов
    private array $requiredMappings;
    private int $applicationId;
    
    
    // Принимает параметры-----------------------------------
    // requiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // applicationId                       int : id заявления
    //
    function __construct(RequiredMappingsSetter $requiredMappings, int $applicationId){
        
        // Проверка классов нужных маппингов
        foreach($requiredMappings->getRequiredMappings() as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $Mapping = new FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);
    
                if(!is_null($Mapping->getErrorCode())){
                    throw new FileException("Ошибка в маппинг таблице в классе {$className}. {$Mapping->getErrorText()}", $Mapping->getErrorCode());
                }
                unset($Mapping);
            }
        }
        
        if(!ApplicationsTable::checkExistById($applicationId)){
            throw new Exception("Заявление с id: {$applicationId} не существует");
        }
        
        $this->requiredMappings = $requiredMappings->getRequiredMappings();
        $this->applicationId = $applicationId;
    }
    
    
    // Предназначен для полученя нужных (is_needs=1) файлов, находящихся в заявлении applicationId
    // и в требуемых маппингах requiredMappings
    // Возвращает параметры-----------------------------------
    // array : структура массива аналогична requiredMappings. Вместо названия класса - массив с нужные файлами / null
    //
    public function getNeedsFiles():array {
    
        $result = [];
        
        foreach($this->requiredMappings as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $files = $className::getNeedsAssocByIdApplication($this->applicationId);
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files;
            }
        }
        return $result;
    }
    
    
    // Принимает параметры-----------------------------------
    // files     array : индексный масив с ассоциативными массивами файлов
    // structure array  : индексный масив с ассоциативными массивами узлов структуры
    //
    public function getFilesInStructure(array $files, array $structure):array {
        
        $result = $structure;
        
        foreach($structure as $structureIndex => $node){
            
            // Индексы файлов, вошедшие в текущий раздел структуры
            $enteredIndexes = [];
    
            foreach($files as $fileIndex => $file){
        
                if($node['id'] == $file['id_structure_node']){
                    $result[$structureIndex]['files'][] = $file;
                    $enteredIndexes[] = $fileIndex;
                }
            }
    
            // Удаляем из массива файлов те, которые уже вошли в раздел структуры
            foreach($enteredIndexes as $index){
                unset($files[$index]);
            }
    
            // Если файлы закончились - выходим из внешнего цикла
            // Данная проверка реализована тут, а не в цикле выше, поскольку функция может принимать и пустой массив files
            if(empty($files)){
                break;
            }
        }
        
        return $result;
    }
}



// Класс предназначен для создания структуры нужных маппингов, по которой в дальнейшем класс FilesInitialization
// будет получать файлы
//
class RequiredMappingsSetter{

    // Массив нужных маппингов
    private array $requiredMappings;
    
    
    // Предназначен для установки всех маппингов 2 уровня, находящихся в указанном маппинге 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    //
    public function setMappingLevel1(int $mapping_level_1):void {
        $this->checkMappingLevel1Exist($mapping_level_1);
        
        foreach(_FILE_TABLE_MAPPING[$mapping_level_1] as $mapping_level_2_code => $className){
            $this->requiredMappings[$mapping_level_1][$mapping_level_2_code] = $className;
        }
    }
    
    
    // Предназначен для установки конкретного маппинга 2 уровня, находящегося в указанном маппинге 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    // mapping_level_2 int : индекс массива _FILE_TABLE_MAPPING[mapping_level_1]
    //
    public function setMappingLevel2(int $mapping_level_1, int $mapping_level_2):void {
        $this->checkMappingLevel2Exist($mapping_level_1, $mapping_level_2);
        $this->requiredMappings[$mapping_level_1][$mapping_level_2] = _FILE_TABLE_MAPPING[$mapping_level_1][$mapping_level_2];
    }
    
    
    // Предназначен для получения массива нужных маппингов
    // Возвращает параметры-----------------------------------
    // array : нужные маппинги
    //
    public function getRequiredMappings():array {
        return $this->requiredMappings;
    }
    
    
    // Предназначен для проверки существования указанного маппинга 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    //
    private function checkMappingLevel1Exist(int $mapping_level_1):void {
        if(!array_key_exists($mapping_level_1, _FILE_TABLE_MAPPING)){
            throw new FileException("Запрашиваемый mapping_level_1: {$mapping_level_1} не существует в _FILE_TABLE_MAPPING");
        }
    }
    
    
    // Предназначен для проверки существования указанного маппинга 2 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    // mapping_level_2 int : индекс массива _FILE_TABLE_MAPPING[mapping_level_1]
    //
    private function checkMappingLevel2Exist(int $mapping_level_1, int $mapping_level_2):void {
        $this->checkMappingLevel1Exist($mapping_level_1);
        if(!array_key_exists($mapping_level_2, _FILE_TABLE_MAPPING[$mapping_level_1])){
            throw new FileException("Запрашиваемый mapping_level_2: {$mapping_level_2} не существует mapping_level_1: {$mapping_level_1} в _FILE_TABLE_MAPPING");
        }
    }
}