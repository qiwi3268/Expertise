<?php


namespace Lib\Files\Initialization;

// Абстрактный класс для инициализации сохраненных файлов
//
abstract class Initializator{
    
    
    private array $filesRequiredMappings; // Массив нужных маппингов файловых таблиц
    private array $signsRequiredMappings; // Массив нужных маппингов таблиц подписей
    private int $applicationId; //todo chieldd
    
    
    // Принимает параметры-----------------------------------
    // filesRequiredMappings RequiredMappingsSetter : объект класса с установленными ранее нужными маппингами
    // applicationId                       int : id заявления
    //
    function __construct(\RequiredMappingsSetter $filesRequiredMappings, int $applicationId){
        
        $signsRequiredMappings = [];
        
        // Проверка классов нужных маппингов
        foreach($filesRequiredMappings->getRequiredMappings() as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $FilesMapping = new \Lib\Files\Mappings\FilesTableMapping($mapping_level_1_code, $mapping_level_2_code);
                
                if(!is_null($FilesMapping->getErrorCode())){
                    throw new \FileException("Ошибка в маппинг таблице (файлов) в классе '{$className}'. '{$FilesMapping->getErrorText()}'", $FilesMapping->getErrorCode());
                }
                unset($FilesMapping);
                
                // Формирование маппингов для таблиц подписей, аналогичных по стркутуре с filesRequiredMappings
                $SignsMapping = new \SignsTableMapping($mapping_level_1_code, $mapping_level_2_code);
                
                $SignsMappingErrorCode = $SignsMapping->getErrorCode();
                
                if(is_null($SignsMappingErrorCode)){
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = $SignsMapping->getClassName();
                }elseif($SignsMappingErrorCode == 1){
                    // Не существует соответствующего класса таблицы подписей к классу файловой таблицы
                    $signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code] = null;
                }else{
                    throw new \FileException("Ошибка в маппинг таблице (подписей) в классе '{$className}'. '{$SignsMapping->getErrorText()}'", $SignsMappingErrorCode);
                }
                unset($SignsMapping);
            }
        }
        
        if(!\ApplicationsTable::checkExistById($applicationId)){
            throw new \Exception("Заявление с id: {$applicationId} не существует");
        }
        
        $this->filesRequiredMappings = $filesRequiredMappings->getRequiredMappings();
        $this->signsRequiredMappings = $signsRequiredMappings;
        $this->applicationId = $applicationId;
    }
    
    
    // Предназначен для полученя нужных (is_needs=1) файлов, находящихся в заявлении applicationId
    // и в требуемых маппингах filesRequiredMappings.
    //
    // Возвращает параметры-----------------------------------
    // array : структура массива аналогична filesRequiredMappings. Вместо названия класса - массив с нужные файлами / null
    //
    //todo метод возвращает не только файлы, а еще и открепленные подписи в этой таблице
    public function getNeedsFiles():array{
        
        var_dump('метод возвращает не только файлы, а еще и открепленные подписи в этой таблице');
        
        $result = [];
        
        foreach($this->filesRequiredMappings as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $className){
                
                $files = $className::getNeedsAssocByIdApplication($this->applicationId);
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files;
            }
        }
        return $result;
    }
    
    
    // Предназначен для полученя нужных (is_needs=1) файлов и подписей к ним, находящихся в заявлении applicationId
    // и в требуемых маппингах filesRequiredMappings
    // У кажого файла есть свойство 'signs', которое:
    //    null, если для данного маппинга файловой таблицы не предусмотрены подписи;
    //    включает в себя массивы подписей встроенных и открепленных: 'internal' = [], 'external' = []
    // *** Из массива файлов будут автоматически удалены файлы, которые являются открепленными подписями
    // Возвращает параметры----------------------------------
    // array : структура массива аналогична filesRequiredMappings. Вместо названия класса - массив с нужные файлами / null
    // Выбрасывает исключения--------------------------------
    // FileException : осталась(лись) подпись, которая не подошла ни к одному из файлов
    //
    public function getNeedsFilesWithSigns():array{
        
        $result = [];
        
        foreach($this->filesRequiredMappings as $mapping_level_1_code => $mapping_level_2){
            
            foreach($mapping_level_2 as $mapping_level_2_code => $fileClassName){
                
                $files = $fileClassName::getNeedsAssocByIdApplication($this->applicationId);
                
                if(is_null($files)){
                    $result[$mapping_level_1_code][$mapping_level_2_code] = null;
                    continue;
                }
                
                $files = new ArrayIterator($files);
                
                // Формирование id файлов для запроса IN
                $ids = [];
                foreach($files as ['id' => $id]){
                    $ids[] = $id;
                }
                
                $signClassName = $this->signsRequiredMappings[$mapping_level_1_code][$mapping_level_2_code];
                
                if(is_null($signClassName) || is_null($signs = $signClassName::getAllAssocByIds($ids))){
                    
                    foreach($files as $index => $file){
                        $files[$index]['signs'] = null;
                    }
                    $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
                    unset($files);
                    continue;
                }
                
                $signs = new ArrayIterator($signs);
                
                // Хэш-массив id файлов для быстрого поиска
                $idsHash = GetHashArray($ids);
                
                $files->rewind();
                while($files->valid()){
                    
                    $fi = $files->key();
                    $file = $files->current();
                    
                    // Делаем проверку, чтобы не обнулить данные, если в file была открепленная подпись и file c данными искался из других элементов
                    if(!isset($files[$fi]['signs']['internal'])) $files[$fi]['signs']['internal'] = [];
                    if(!isset($files[$fi]['signs']['external'])) $files[$fi]['signs']['external'] = [];
                    
                    $signs->rewind();
                    while($signs->valid()){
                        
                        $si = $signs->key();
                        $sign = $signs->current();
                        
                        // Итерируемый file является встроенной подписью
                        if($file['id'] == $sign['id_sign'] && $sign['is_external'] == 0 && is_null($sign['id_file'])){
                            
                            $files[$fi]['signs']['internal'][] = $sign;
                            $signs->offsetUnset($si);
                            continue;
                            
                            // Итерируемый file является файлом, к которому есть открепленная подпись
                        }elseif($file['id'] == $sign['id_file'] && $sign['is_external'] == 1 && isset($idsHash[$sign['id_sign']])){
                            
                            // Находим и удаляем file открепленной подписи
                            $externalSignFile = array_filter($files->getArrayCopy(), fn($tmp) => $tmp['id'] == $sign['id_sign']);
                            $files->offsetUnset(array_key_first($externalSignFile));
                            $files[$fi]['signs']['external'][] = $sign;
                            
                            $signs->offsetUnset($si);
                            continue;
                            
                            // Итерируемый file является открепленной подписью к другому file
                        }elseif($file['id'] == $sign['id_sign'] && $sign['is_external'] == 1 && isset($idsHash[$sign['id_file']])){
                            
                            // Находим file с данными
                            unset($ind);
                            foreach($files->getArrayCopy() as $tmp_index => $tmp_file){
                                if($tmp_file['id'] == $sign['id_file']){
                                    $ind = $tmp_index;
                                    break;
                                }
                            }
                            $files[$ind]['signs']['external'][] = $sign;
                            
                            $files->offsetUnset($fi); // Удаляем итерируемый file (открепленную подпись)
                            $signs->offsetUnset($si);
                            continue 2;
                        }
                        $signs->next();
                    }
                    $files->next();
                }
                
                if($signs->count() > 0){
                    
                    $ids = [];
                    foreach($signs as ['id' => $id]) $ids[] = $sign['id'];
                    $ids = implode(', ', $ids);
                    // Вероятнее всего, требуемый файл не попал в выборку getNeedsAssocByIdApplication по причине is_needs=0
                    throw new FileException("Осталась(лись) подпись с id: '{$ids}' из таблицы подписей: '{$signClassName}', которая не подошла ни к одному из файлов");
                }
                $result[$mapping_level_1_code][$mapping_level_2_code] = $files->getArrayCopy();
            }
        }
        return $result;
    }
}
