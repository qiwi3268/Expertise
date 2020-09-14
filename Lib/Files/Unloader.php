<?php


namespace Lib\Files;


/**
 * Предназначен для выгрузки файлов
 *
 */
class Unloader
{

    /**
     * Предназначен для выгрузки (отдачи) файла в браузер
     *
     * После выгрузки файла скрипт завершает свое выполнение
     *
     * @param string $fsName абсолютный путь в ФС сервера к выгружаемому файлу
     * @param string $unloadName имя файла для выгрузки
     */
    static public function unload(string $fsName, string $unloadName): void
    {
        // Сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($unloadName));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fsName));
        readfile($fsName);
        exit();
    }
}
