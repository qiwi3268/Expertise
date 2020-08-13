<?php


class FontAwesome5Helper{
    
    
    // Предназначен для установки файловой иконки в свойство file_icon
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setFileIconClass(array &$files):void {
        
        foreach($files as &$file){
            
            $name = $file['file_name'];

            if(mb_strpos($name, '.pdf') !== false){
                $class = 'fa-File-pdf';
            }elseif(mb_strpos($name, '.docx') !== false){
                $class = 'fa-File-word';
            }elseif(mb_strpos($name, '.xlsx') !== false){
                $class = 'fa-File-excel';
            }else{
                $class = 'fa-File-alt';
            }
            
            $file['file_icon'] = $class;
        }
        unset($file);
    }
    
}
