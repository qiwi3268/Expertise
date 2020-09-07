<?php


namespace Tables\Responsible\Interfaces;


// Общий интерфейс для работы с таблицами ответственных
//
interface Responsible
{
    // Предназначен для удаления всех ответственных
    //
<<<<<<< HEAD
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void;

    // Предназначен для получения ассоциативных массивов с ответственными
    //
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array;
=======
    static public function deleteResponsible(int $id_main_document): void;

    // Предназначен для получения ассоциативных массивов с ответственными
    //
    static public function getResponsible(int $id_main_document): ?array;
>>>>>>> a079240578d525ed83df11c016d74a5d605155e8
}