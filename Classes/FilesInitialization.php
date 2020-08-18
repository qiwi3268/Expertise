<?php


// Класс инициализации сохраненных файлов в рамках заявления
//
class FilesInitialization{
    
    
    private array $filesRequiredMappings; // Массив нужных маппингов файловых таблиц
    private array $signsRequiredMappings; // Массив нужных маппингов таблиц подписей
    private int $applicationId;
    
    
    // Принимает параметры-----------------------------------
    // filesRequiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // applicationId                       int : id заявления
    //
    function __construct(RequiredMappingsSetter $filesRequiredMappings, int $applicationId){
        
        $signsRequiredMappings = [];
        
        // Проверка классов нужных маппингов
        foreach($filesRequiredMappings->getRequiredMappings() as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $FilesMapping = new FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);
    
                if(!is_null($FilesMapping->getErrorCode())){
                    throw new FileException("Ошибка в маппинг таблице (файлов) в классе '{$className}'. '{$FilesMapping->getErrorText()}'", $FilesMapping->getErrorCode());
                }
                unset($FilesMapping);
                
                // Формирование маппингов для таблиц подписей, аналогичных по стркутуре с filesRequiredMappings
                $SignsMapping = new SignsTableMapping($mapping_level_1_code, $mapping_level_2_code);
                
                $SignsMappingErrorCode = $SignsMapping->getErrorCode();
                
                if(is_null($SignsMappingErrorCode)){
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = $SignsMapping->getClassName();
                }elseif($SignsMappingErrorCode == 1){
                    // Не существует соответствующего класса таблицы подписей к классу файловой таблицы
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = null;
                }else{
                    throw new FileException("Ошибка в маппинг таблице (подписей) в классе '{$className}'. '{$SignsMapping->getErrorText()}'", $SignsMappingErrorCode);
                }
                unset($SignsMapping);
            }
        }
        
        if(!ApplicationsTable::checkExistById($applicationId)){
            throw new Exception("Заявление с id: {$applicationId} не существует");
        }
        
        $this->filesRequiredMappings = $filesRequiredMappings->getRequiredMappings();
        $this->signsRequiredMappings = $signsRequiredMappings;
        $this->applicationId = $applicationId;
    }
    
    
    // Предназначен для полученя нужных (is_needs=1) файлов, находящихся в заявлении applicationId
    // и в требуемых маппингах filesRequiredMappings
    // Возвращает параметры-----------------------------------
    // array : структура массива аналогична filesRequiredMappings. Вместо названия класса - массив с нужные файлами / null
    //
    //todo метод возвращает не только файлы, а еще и открепленные подписи в этой таблице
    public function getNeedsFiles():array {
    
        $result = [];
        
        foreach($this->filesRequiredMappings as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $files = $className::getNeedsAssocByIdApplication($this->applicationId);
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files;
            }
        }
        return $result;
    }
    
    public function getNeedsFilesWithSigns():array {
    
        $result = [];
    
        foreach($this->filesRequiredMappings as $mapping_level_1_code => $mapping_level_2){
        
            foreach($mapping_level_2 as $mapping_level_2_code => $fileClassName){
            
                $files = $fileClassName::getNeedsAssocByIdApplication($this->applicationId);
                
                $signClassName = $this->signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code];
                
                if(is_null($signClassName)){
                    foreach($files as &$file) $file['signs'] = null;
                    unset($file);
                    continue;
                }
                
                $ids = [];
                foreach($files as $file){
                    $ids[] = $file['id'];
                }
                $signs = $signClassName::getAllAssocByIds($ids);
                
                $idsHash = GetHashArray($ids);
                
                // Создаем Iterator, поскольку в рамках обхода файлов требуется удалять открепленные подписи
                $Iterator = new ArrayIterator($files);
    
                foreach($Iterator as $file_index => $file){
    
                    $Iterator[$file_index]['signs']['internal'] = [];
                    $Iterator[$file_index]['signs']['external'] = [];
                    
                    unset($tmp, $ind);
                    
                    foreach($signs as $sign_index => $sign){
                        
                        // Итерируемый file является встроенной подписью
                        if($file['id'] == $sign['id_sign'] && $sign['is_external'] == 0 && is_null($sign['id_file'])){
    
                            $Iterator[$file_index]['signs']['internal'][] = $sign;
    
                            unset($signs[$sign_index]); // Удаляем итерируемую sign
                            
                        // Итерируемый file является файлом, к которому есть открепленная подпись
                        }elseif($file['id'] == $sign['id_file'] && $sign['is_external'] == 1){
                        
                            if(!isset($idsHash[$sign['id_sign']])){
                                throw new FileException("Для файла id: '{$file['id']}', таблицы '{$fileClassName}' отсутствует файл открепленной подписи id: '{$sign['id_sign']}'");
                            }
    
                            // Находим file открепленной подписи
                            $externalSignFile = array_filter($Iterator->getArrayCopy(), fn($tmp) => $tmp['id'] == $sign['id_sign']);
                            $ind = array_key_first($externalSignFile);
                            // Удаляем file открепленной подписи
                            unset($Iterator[$file_index]);
                            
                            $Iterator[$file_index]['signs']['external'][] = $sign;
                            
                            unset($signs[$sign_index]); // Удаляем итерируемую sign
                        
                        // Итерируемый file является открепленной подписью к другому file
                        }elseif($file['id'] == $sign['id_sign'] && $sign['is_external'] == 1){
    
                            if(!isset($idsHash[$sign['id_file']])){
                                throw new FileException("Для файла открепленной подписи id: '{$sign['id_sign']}', таблицы '{$fileClassName}' отсутствует файл id: '{$sign['id_file']}'");
                            }
                            
                            // Удаляем итерируемый file (открепленную подпись)
                            unset($Iterator[$file_index]);
                            // Находим file с данными
                            $dataFile = array_filter($Iterator->getArrayCopy(), fn($tmp) => $tmp['id'] == $sign['id_file']);
                            $ind = array_key_first($dataFile);
                            $Iterator[$ind]['signs']['external'][] = $sign;
    
                            unset($signs[$sign_index]); // Удаляем итерируемую sign
                        }
                    }
                }
            }
            $result[$mapping_level_1_code][$mapping_level_2_code] = $Iterator->getArrayCopy();
        }
        return $result;
    }
    
    
    
    public function getFilesSigns(array $needsFiles) {
        foreach($needsFiles as $mapping_level_1_code => $mapping_level_2){
            foreach($mapping_level_2 as $mapping_level_2_code => $files){
                
                $signMapping = $this->signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code];
                
                
                var_dump($mapping_level_1_code);
                var_dump($mapping_level_2_code);
                var_dump($files);
            }
        }
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

    // Массив нужных маппингов файловых таблиц
    private array $filesRequiredMappings;
    
    
    
    // Предназначен для установки всех маппингов 2 уровня, находящихся в указанном маппинге 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    //
    public function setMappingLevel1(int $mapping_level_1):void {
        $this->checkMappingLevel1Exist($mapping_level_1);
        
        foreach(_FILE_TABLE_MAPPING[$mapping_level_1] as $mapping_level_2_code => $className){
            $this->filesRequiredMappings[$mapping_level_1][$mapping_level_2_code] = $className;
        }
    }
    
    
    // Предназначен для установки конкретного маппинга 2 уровня, находящегося в указанном маппинге 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    // mapping_level_2 int : индекс массива _FILE_TABLE_MAPPING[mapping_level_1]
    //
    public function setMappingLevel2(int $mapping_level_1, int $mapping_level_2):void {
        $this->checkMappingLevel2Exist($mapping_level_1, $mapping_level_2);
        $this->filesRequiredMappings[$mapping_level_1][$mapping_level_2] = _FILE_TABLE_MAPPING[$mapping_level_1][$mapping_level_2];
    }
    
    
    // Предназначен для получения массива нужных маппингов
    // Возвращает параметры-----------------------------------
    // array : нужные маппинги
    //
    public function getRequiredMappings():array {
        return $this->filesRequiredMappings;
    }
    
    
    // Предназначен для проверки существования указанного маппинга 1 уровня
    // Принимает параметры-----------------------------------
    // mapping_level_1 int : индекс массива _FILE_TABLE_MAPPING
    //
    private function checkMappingLevel1Exist(int $mapping_level_1):void {
        if(!array_key_exists($mapping_level_1, _FILE_TABLE_MAPPING)){
            throw new FileException("Запрашиваемый mapping_level_1: '{$mapping_level_1}' не существует в _FILE_TABLE_MAPPING");
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
            throw new FileException("Запрашиваемый mapping_level_2: '{$mapping_level_2}' не существует mapping_level_1: '{$mapping_level_1}' в _FILE_TABLE_MAPPING");
        }
    }
}