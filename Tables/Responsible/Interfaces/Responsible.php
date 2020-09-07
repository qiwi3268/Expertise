<?php


namespace Tables\Responsible\Interfaces;


// Общий интерфейс для работы с таблицами ответственных
//
interface Responsible
{
    // Предназначен для удаления всех ответственных
    //
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void;

    // Предназначен для получения ассоциативных массивов с ответственными
    //
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array;
}