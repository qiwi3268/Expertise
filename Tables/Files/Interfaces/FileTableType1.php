<?php


namespace Tables\Files\Interfaces;


interface FileTableType1 extends FileTable
{

    // Предназначен для создания записи в файловой таблице
    //
    static public function create(int $id_main_document, string $file_name, int $file_size, string $hash): int;
}