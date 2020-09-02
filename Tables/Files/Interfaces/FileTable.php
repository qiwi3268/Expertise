<?php


namespace Tables\Files\Interfaces;


interface FileTable
{

    // Предназначен для удаления записи из файловой таблицы
    //
    static public function deleteById(int $id): void;

    // Предназначен для установки флага загрузки файла на сервер
    //
    static public function setUploadedById(int $id): void;

    // Предназначен для получения ассоциативного массива записи по id
    //
    static public function getAssocById(int $id): ?array;

    // Предназначен для получения ассоциативного массива записи по по id главного документа и её hash'у
    //
    static public function getAssocByIdMainDocumentAndHash(int $id_application, string $hash): ?array;

    // Предназначен для получения id записи по id главного документа и её hash'у
    //
    static public function getIdByIdMainDocumentAndHash(int $id_main_document, string $hash): ?int;

    // Предназначен для проверки существования записи по id
    //
    static public function checkExistById(int $id): bool;

    // Предназначен для получения ассоциативных массивов нужных файлов к главному документу по его id
    //
    static public function getNeedsAssocByIdMainDocument(int $id_main_document): ?array;

    // Предназнчен для получения ассоциативного массива ненужных файлов
    //
    static public function getNoNeedsAssoc(): ?array;

    // Предназначен для установки поля 'is_needs' в 1 по id записи
    //
    static public function setIsNeedsToTrueById(int $id): void;

    // Предназначен для установки поля 'is_needs' в 0 по id записи
    //
    static public function setIsNeedsToFalseById(int $id): void;

    // Предназначен для установки флага удаления крона по id
    //
    static public function setCronDeletedFlagById(int $id): void;
}