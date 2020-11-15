<?php


namespace Tables\Files\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;
use Tables\CommonTraits\deleteById as deleteByIdTrait;



/**
 * Реализует интерфейс {@see \Tables\Files\Interfaces\FileTable}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait FileTable
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::deleteById()}
     */
    use deleteByIdTrait;


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::checkExistByHash()}
     *
     * @param string $hash
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistByHash(string $hash): bool
    {
        $table = self::$tableName;

        $query = "SELECT COUNT(*)>0
                  FROM `{$table}`
                  WHERE `hash`=?";
        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$hash])[0];
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::setUploadedById()}
     *
     * @param int $id
     * @throws DataBaseEx
     */
    static public function setUploadedById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_uploaded`='1'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::getAssocById()}
     *
     * @param int $id
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAssocById(int $id): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::getAllAssocByIds()}
     *
     * @param int[] $ids
     * @return array
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereNeedsByIds(array $ids): ?array
    {
        $table = self::$tableName;

        $condition = TableHelper::getConditionForIN($ids);

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id` IN ({$condition}) AND `is_needs`='1'";
        $result = ParametrizedQuery::getFetchAssoc($query, $ids);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::getAssocByHash()}
     *
     * @param string $hash
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAssocByHash(string $hash): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `hash`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$hash]);
        return $result ? $result[0] : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::getAllAssocWhereNeedsByIdMainDocument()}
     *
     * @param int $id_main_document
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereNeedsByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `is_needs`='1'";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::getAllAssocWhereNoNeeds()}
     *
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getAllAssocWhereNoNeeds(): ?array
    {
        $table = self::$tableName;

        $query = "SELECT *
                  FROM `{$table}`
                  WHERE `is_needs`=0";

        $result = SimpleQuery::getFetchAssoc($query);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::setNeedsToTrueById()}
     *
     * @param int $id
     * @throws DataBaseEx
     */
    static public function setNeedsToTrueById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_needs`='1', `cron_deleted_flag`='0', `date_cron_deleted_flag`=NULL
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::setNeedsToFalseById()}
     *
     * @param int $id
     * @throws DataBaseEx
     */
    static public function setNeedsToFalseById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `is_needs`='0'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTable::setCronDeletedFlagById()}
     *
     * @param int $id
     * @throws DataBaseEx
     */
    static public function setCronDeletedFlagById(int $id): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `cron_deleted_flag`='1', `date_cron_deleted_flag`=UNIX_TIMESTAMP()
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }
}