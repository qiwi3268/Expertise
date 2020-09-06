<?php


namespace Lib\Singles\Helpers;


class FileHandler
{

    // Предназначен для установки файловой иконки FontAwesome5 в свойство file_icon
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


    // Предназначен для установки файловой иконки FontAwesome5 в свойство file_icon
    // Предназначен для установки результов валидации подписей в виде json'а в свойство validate_results
    // Если подпись не прдусмотрена для данного файла впринципе, то validate_results = null;
    // Если подписей нет, то validate_results = '';
    // Если подписи есть, то json с результатами.
    // Открепленные и встроенные подписи находятся в одном месте
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setValidateResultJSON(array &$files): void
    {
        foreach ($files as &$file) {

            if (is_null($file['signs'])) {
                $file['validate_results'] = null;
                continue;
            }

            $signs = array_merge($file['signs']['external'], $file['signs']['internal']);
            $json = [];

            foreach ($signs as $sign) {
                $json[] = [
                    'fio'         => $sign['fio'],
                    'certificate' => $sign['certificate'],
                    'signature_verify' => [
                        'result'       => (bool)$sign['signature_result'],
                        'user_message' => $sign['signature_user_message']
                    ],
                    'certificate_verify' => [
                        'result'         => (bool)$sign['certificate_result'],
                        'user_message'   => $sign['certificate_user_message']
                    ]
                ];
            }
            $file['validate_results'] = empty($json) ? '' : json_encode($json);
        }
        unset($file);
    }


    // Предназначен для установки человекопонятного размера файла в свойство human_file_size
    // Принимает параметры-----------------------------------
    // &files array : ссылка на массив с файлами
    //
    static public function setHumanFileSize(array &$files): void
    {
        foreach ($files as &$file) {
            $file['human_file_size'] = getHumanFileSize($file['file_size']);
        }
        unset($file);
    }
}