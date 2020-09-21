<?php


namespace Tables\Files\Interfaces;


/**
 * Интерфейс для работы с файловыми таблицами типа:
 *
 * - id
 * - id_main_document
 * - file_name
 * - file_size
 * - hash
 * - is_uploaded
 * - is_needs
 * - cron_deleted_flag
 * - date_cron_deleted_flag
 *
 */
interface FileTableType1 extends FileTable
{

    /**
     * Предназначен для создания записи в файловой таблице
     *
     * @param int $id_main_document id главного документа
     * @param string $file_name настоящее имя файла
     * @param int $file_size размер файла
     * @param string $hash hash файла (имя файла в ФС сервера)
     * @return int id созданной записи
     */
    static public function create(
        int $id_main_document,
        string $file_name,
        int $file_size,
        string $hash
    ): int;
}