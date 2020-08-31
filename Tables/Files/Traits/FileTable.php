<?php


namespace Tables\Files\Traits;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


// Трейт, реализующий интерфейс Tables\Files\Interfaces\FileTable
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait FileTable
{

    // Предназначен для удаления записи из файловой таблицы
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function deleteById(int $id): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для установки флага загрузки файла на сервер
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setUploadedById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_uploaded`='1'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для получения ассоциативного массива записи по id
    // Принимает параметры-----------------------------------
    // id int : id записи
    // Возвращает параметры-----------------------------------
    // array : в случае, если запись существует
    // null  : в противном случае
    //
    static public function getAssocById(int $id): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  where `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


    // Предназначен для получения ассоциативного массива записи по по id главного документа и её hash'у
    // Принимает параметры-----------------------------------
    // id_main_document int : id главного документа
    // hash          string : hash файла
    // Возвращает параметры-----------------------------------
    // array : в случае, если запись существует
    // null  : в противном случае
    //
    static public function getAssocByIdMainDocumentAndHash(int $id_main_document, string $hash): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  where `id_main_document`=? AND `hash`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document, $hash]);
        return $result ? $result[0] : null;
    }


    // Предназначен для получения id записи по id главного документа и её hash'у
    // Принимает параметры-----------------------------------
    // id_main_document int : id главного документа
    // hash          string : hash записи
    // Возвращает параметры-----------------------------------
    // int  : в случае, если запись существует
    // null : в противном случае
    //
    static public function getIdByIdMainDocumentAndHash(int $id_main_document, string $hash): ?int
    {
        $table = self::$tableName;

        $query = "SELECT `id`
                  FROM `{$table}`
                  where `id_main_document`=? AND `hash`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document, $hash]);
        return $result ? $result[0] : null;
    }


    // Предназначен для проверки существования записи по id
    // Принимает параметры-----------------------------------
    // id  int : id записи
    // Возвращает параметры----------------------------------
    // true   : запись существует
    // false  : запись не существует
    //
    static public function checkExistById(int $id): bool
    {
        $table = self::$tableName;

        $query = "SELECT count(*)>0
                  FROM `{$table}`
                  WHERE `id`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }


    // Предназначен для получения ассоциативного массива нужных файлов к главному документу по его id
    // Принимает параметры-----------------------------------
    // id_main_document int : id главного документа
    // Возвращает параметры----------------------------------
    // array : в случае, если запись сущестует(ют)
    // null  : в противном случае
    //
    static public function getNeedsAssocByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `is_needs`='1'";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    // Предназнчен для получения ассоциативного массива ненужных файлов
    // Возвращает параметры-----------------------------------
    // array : в случае, если записи существуют
    // null  : в противном случае
    //
    static public function getNoNeedsAssoc(): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `is_needs`=0";

        $result = SimpleQuery::getFetchAssoc($query);

        return $result ? $result : null;
    }


    // Предназначен для установки поля 'is_needs' в 1 по id записи
    // Помимо установки флага is_needs - удаляем флаг cron_deleted_flag и date_cron_deleted_flag
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setIsNeedsToTrueById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_needs`='1', `cron_deleted_flag`='0', `date_cron_deleted_flag`=NULL
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для установки поля 'is_needs' в 0 по id записи
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setIsNeedsToFalseById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_needs`='0'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Предназначен для установки флага удаления крона по id
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function setCronDeletedFlagById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `cron_deleted_flag`='1', `date_cron_deleted_flag`=UNIX_TIMESTAMP()
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
}