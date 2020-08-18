<?php


// Трейт, реализующий интерфейс Interface_signTable
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait  Trait_signTable{
    
    
    // Реализация метода интерфейса
    // Предназначен для создания записи в таблице подписей
    // Принимает параметры-----------------------------------
    // id_sign                     int : id подписи (и встроенной и открепленной) из файловой таблицы
    // is_external                 int : Флаг открепленной подписи
    // id_file                    ?int : Если is_external=1, то это id файла из файловой таблицы, к которому принадлежит данная открепленная подпись
    // fio                      string : ФИО подписанта
    // certificate              string : Данные из сертификата подписанта
    // signature_result         string : Результат проверки подписи
    // signature_message        string : Сообщение из КриптоПро о результате проверки подписи
    // signature_user_message   string : Сообщение для пользователя о результате проверки подписи
    // certificate_result       string : Результат проверки сертификата
    // certificate_message      string : Сообщение из КриптоПро о результате проверки подписи(сертификата)
    // certificate_user_message string : Сообщение для пользователя о результате проверки сертификата
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_sign,
                                  int $is_external,
                                  ?int $id_file,
                                  string $fio,
                                  string $certificate,
                                  string $signature_result,
                                  string $signature_message,
                                  string $signature_user_message,
                                  string $certificate_result,
                                  string $certificate_message,
                                  string $certificate_user_message):int {
    
        $table = self::$tableName;
    
        $id_file_part = is_null($id_file) ? 'NULL' : '?';
        
        $query = "INSERT INTO `{$table}`
                    (`id`,
                     `id_sign`,
                     `is_external`,
                     `id_file`,
                     `fio`,
                     `certificate`,
                     `signature_result`,
                     `signature_message`,
                     `signature_user_message`,
                     `certificate_result`,
                     `certificate_message`,
                     `certificate_user_message`)
                    VALUES
                      (NULL, ?, ?, {$id_file_part}, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // bind-параметры до и после id_file
        $bindParams_before = [$id_sign,
                              $is_external];
        $bindParams_after = [$fio,
                             $certificate,
                             $signature_result,
                             $signature_message,
                             $signature_user_message,
                             $certificate_result,
                             $certificate_message,
                             $certificate_user_message];
  
        $bindParams = is_null($id_file) ? [...$bindParams_before, ...$bindParams_after] : [...$bindParams_before, $id_file, ...$bindParams_after];
        
        return ParametrizedQuery::set($query, $bindParams);
    }
    
    static public function getAllAssocByIds(array $ids):?array {
        
        $table = self::$tableName;
        
        $in = '('.implode(', ', $ids).')';
        
        $query = "SELECT *
                  FROM `$table`
                  WHERE `id_sign` IN {$in} OR `id_file` IN {$in}";
        
        $result = SimpleQuery::getFetchAssoc($query);
        
        return $result ? $result : null;
    }
}