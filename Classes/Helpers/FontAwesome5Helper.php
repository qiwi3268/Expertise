<?php


// Вспомогательный класс. Предназначен для вспомогательной работы с FontAwesome
//
class FontAwesome5Helper{
    
    
    // Предназначен для установки файловой иконки в свойство file_icon
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setFileIconClass(array &$files):void {
        
        foreach($files as &$file){
            
            $name = $file['file_name'];

            if(contains($name, '.pdf')) $class = 'fa-File-pdf';
            elseif(contains($name, '.docx')) $class = 'fa-File-word';
            elseif(contains($name, '.xlsx')) $class = 'fa-File-excel';
            else $class = 'fa-File-alt';
            
            $file['file_icon'] = $class;
        }
        unset($file);
    }
    
}
