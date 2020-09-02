<?php


namespace Classes\Application\Helpers;

use core\Classes\Session;


// Предназначен для вспомогательной работы с заявлением
//
class Helper{


    // Предназначен для получения числового имени заявления,
    // дополняет внутренний счетчик ведущим нулем
    // Принимает параметры-----------------------------------
    // internalCounter int : внутренний счетчик заявления
    // Возвращает параметры----------------------------------
    // string : числовое имя
    //
    static public function getInternalAppNumName(int $internalCounter):string {

        $nowDate = date('Y-m');

        if($internalCounter < 10){
            $internalCounter = str_pad($internalCounter, 2,'0', STR_PAD_LEFT);
        }

        return "$nowDate-$internalCounter";
    }


    // Предназначен для парсинга абсолютного пути в ФС серерва к файлу заявления
    // Принимает параметры-----------------------------------
    // path string : абсолютный путь в ФС сервера к файлу
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив с параметрами:
    //    application_id int : id заявления
    //    file_name   string : имя файла в ФС
    //
    static public function parseApplicationFilePath($path):array {

        // Экранирование абсолютного путь в ФС сервера к директории файлов заявлений
        $applicationDir = preg_quote(APPLICATIONS_FILES, '/');

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
        $matches =  GetHandlePregMatch($pattern, $path, false);

        return [
            'application_id' => (int)$matches[1],
            'file_name'      => $matches[2]
        ];
    }
}
