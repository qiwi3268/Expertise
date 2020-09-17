<?php


namespace Tables\Responsible\type_4\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Responsible\type_4\Interfaces\ResponsibleType4}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait ResponsibleType4
{

    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Responsible\type_4\Interfaces\ResponsibleType4::deleteResponsibleByIdMainDocument()
     * @param int $id_main_document
     * @throws DataBaseEx
     */
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void
    {
        $table = self::$tableName;

        $query = "DELETE FROM `{$table}`
                  WHERE `id_main_document`=?";
        ParametrizedQuery::set($query, [$id_main_document]);
    }


    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Responsible\type_4\Interfaces\ResponsibleType4::getResponsibleByIdMainDocument()
     * @param int $id_main_document
     * @return array|null
     * @throws DataBaseEx
     */
    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `user`.`id` as `user_id`,
                         `user`.`last_name`,
                         `user`.`first_name`,
                         `user`.`middle_name`
                  FROM `{$table}`
                  INNER JOIN `user`
                     ON `{$table}`.`id_user` = `user`.`id`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Responsible\type_4\Interfaces\ResponsibleType4::create()
     * @param int $id_main_document
     * @param int $id_user
     * @return int
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_user): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_user`)
                  VALUES
                    (?, ?)";
        return ParametrizedQuery::set($query, [$id_main_document, $id_user]);
    }
}

