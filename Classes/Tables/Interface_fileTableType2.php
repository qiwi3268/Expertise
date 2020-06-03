<?php


// Интерфейс для файловых таблиц типа:
// id   id_application   id_structure_node   file_name   hash   is_uploaded
// (2-й уровень _FILE_TABLE_MAPPING)
//
interface Interface_fileTableType2 extends Interface_fileTable{

    // Предназначен для создания записи в файловой таблице
    //
    static public function create(int $id_application, int $id_structure_node, string $file_name, string $hash):int;
}