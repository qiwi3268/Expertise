<?php


namespace Lib\Singles\Helpers;


/**
 * Вспомогательный класс для обработки файлов
 *
 */
class FileHandler
{

    /**
     * Предназначен для установки файловой иконки FontAwesome5 в свойство <i>file_icon</i>
     *
     * @param array $files <i>ссылка</i> на массив с файлами
     */
    static public function setFileIconClass(array &$files): void
    {
        foreach ($files as &$file) {

            $name = $file['file_name'];

            if (contains($name, '.pdf'))      $class = 'fa-file-pdf';
            elseif (contains($name, '.docx')) $class = 'fa-file-word';
            elseif (contains($name, '.xlsx')) $class = 'fa-file-excel';

            else $class = 'fa-file-alt';

            $file['file_icon'] = $class;
        }
        unset($file);
    }


    /**
     * Предназначен для установки результов валидации подписей в виде json'а в свойство <i>validate_results</i>
     *
     * Если подпись не предусмотрена для данного файла впринципе
     * (отсутствует класс в SIGN_TABLE_MAPPING), то validate_results = <i>null</i><br>
     * Если подписей нет, то validate_results = ''<br>
     * Если подписи есть, то json с результатами<br>
     * Открепленные и встроенные подписи находятся в одном месте<br>
     *
     * @param array $files <i>ссылка</i> на массив с файлами
     */
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


    /**
     * Предназначен для установки результатов валидации подписи с учетом повторяющихся файлов
     *
     * Передает методу {@see \Lib\Singles\Helpers\FileHandler::setValidateResultJSON()}
     * только уникальные файлы, которым будет записано свойство <i>validate_results</i>
     *
     * @param array $files <i>ссылка</i> на массив с файлами
     */
    static public function calculateLinkValidateResultJSON(array &$files): void
    {
        $handleIds = [];
        $links = [];

        foreach ($files as &$file) {

            $fileId = $file['id'];

            if (!isset($handleIds[$fileId])) {

                $handleIds[$fileId] = true;
                $links[] = &$file;
            }
        }
        unset($file);
        self::setValidateResultJSON($links);
    }


    /**
     * Предназначен для установки человекопонятного размера файла в свойство <i>human_file_size</i>
     *
     * @param array $files <i>ссылка</i> на массив с файлами
     */
    static public function setHumanFileSize(array &$files): void
    {
        foreach ($files as &$file) {
            $file['human_file_size'] = getHumanFileSize($file['file_size']);
        }
        unset($file);
    }
}