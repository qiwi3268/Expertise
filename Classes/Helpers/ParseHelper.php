<?php


// Вспомогательный класс. Предназначен для вспомогательной работы с парсингом строк
//
class ParseHelper{
    
    
    // Предназначен для парсинга
    //
    static public function parseApplicationFilePath($path):array {
        
        // Экранирование абсолютного путь в ФС сервера к директории файлов заявлений
        $applicationDir = preg_quote(_APPLICATIONS_FILES_, '/');
        
        // директория файлов заявлений
        // слэш
        // 1 группа:
        //    любая цифра один и более раз
        // слэш
        // 2 группа:
        //   любой не пробельный символ один и более раз
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern ="/{$applicationDir}\/(\d+)\/(\S+)/iu";
        $matches =  GetHandlePregMatch($pattern, $path, false); // Возвращаем результат первой группы
        
        return ['application_id' => (int)$matches[1],
                'file_name'      => $matches[2]
        ];
    }
}
