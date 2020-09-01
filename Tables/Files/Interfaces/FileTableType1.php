<?php


namespace Tables\Files\Interfaces;


// Интерфейс для файловых таблиц типа:
//    id
//    id_main_document
//    file_name
//    file_size
//    hash
//    is_uploaded
//    is_needs
//    cron_deleted_flag
//    date_cron_deleted_flag
//
interface FileTableType1 extends FileTable
{

    // Предназначен для создания записи в файловой таблице
    //
    static public function create(int $id_main_document, string $file_name, int $file_size, string $hash): int;
}