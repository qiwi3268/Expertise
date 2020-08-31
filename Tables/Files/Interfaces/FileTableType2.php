<?php


namespace Tables\Files\Interfaces;


// Интерфейс для файловых таблиц типа:
// id   id_main_document   id_structure_node   file_name   file_size   hash   is_uploaded   is_needs   cron_deleted_flag
//
interface FileTableType2 extends FileTable
{

    // Предназначен для создания записи в файловой таблице
    //
    static public function create(int $id_main_document, int $id_structure_node, string $file_name, int $file_size, string $hash): int;
}