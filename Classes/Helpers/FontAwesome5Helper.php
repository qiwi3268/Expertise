<?php


class FontAwesome5Helper{
<<<<<<< HEAD
    
    
    // Предназначен для установки файловой иконки в свойство file_icon
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setFileIconClass(array &$files):void {
        
        foreach($files as &$file){
            
            $name = $file['file_name'];

            if(mb_strpos($name, '.pdf') !== false){
                $class = 'fa-file-pdf';
            }elseif(mb_strpos($name, '.docx') !== false){
                $class = 'fa-file-word';
            }elseif(mb_strpos($name, '.xlsx') !== false){
                $class = 'fa-file-excel';
            }else{
                $class = 'fa-file-alt';
            }
            
            $file['file_icon'] = $class;
        }
        unset($file);
    }
    
=======

>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
}
