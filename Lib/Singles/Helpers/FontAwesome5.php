<?php


namespace Lib\Singles\Helpers;


// Вспомогательный класс. Предназначен для вспомогательной работы с FontAwesome5
//
class FontAwesome5
{


    // Предназначен для установки файловой иконки в свойство file_icon
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setFileIconClass(array &$files): void
    {

        foreach ($files as &$file) {

            $name = $file['file_name'];

            if (containsAll($name, '.pdf'))      $class = 'fa-File-pdf';
            elseif (containsAll($name, '.docx')) $class = 'fa-File-word';
            elseif (containsAll($name, '.xlsx')) $class = 'fa-File-excel';
            else $class = 'fa-File-alt';

            $file['file_icon'] = $class;
        }
        unset($file);
    }
}
