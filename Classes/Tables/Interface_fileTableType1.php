<?php


// Интерфейс для файловых таблиц типа:
// id   id_application   file_name   hash   is_uploaded
// (1-й уровень _FILE_TABLE_MAPPING)
//
interface Interface_fileTableType1 extends Interface_fileTable{

    static public function create(int $id_application, string $file_name, string $hash):int;
}
