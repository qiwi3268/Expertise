<?php


namespace Tables\Responsible\type_2\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Responsible\type_2\Interfaces\ResponsibleType2}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait ResponsibleType2
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Responsible\type_2\Interfaces\ResponsibleType2::create()}
     *
     * @param int $id_main_document
     * @param int $id_user_info_role
     * @return int
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_user_info_role): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_user_info_role`)
                  VALUES
                    (?, ?)";
        return ParametrizedQuery::set($query, [$id_main_document, $id_user_info_role]);
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Responsible\type_2\Interfaces\ResponsibleType2::getResponsibleByIdMainDocument()}
     *
     * @param int $id_main_document
     * @return array|null
     * @throws DataBaseEx
     */
    public static function getResponsibleByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `{$table}`.`id_user_info_role`,
	                     `user`.`id` as `user_id`,
	                     `user`.`last_name`,
                         `user`.`first_name`,
                         `user`.`middle_name`
                  FROM `user`
                  JOIN `user_role`
                     ON (`user`.`id`=`user_role`.`id_user`)
                  JOIN `{$table}`
                     ON (`user_role`.`id_user_info_role`=`{$table}`.`id_user_info_role`)
                  WHERE `{$table}`.`id_main_document`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Responsible\type_2\Interfaces\ResponsibleType2::deleteResponsibleByIdMainDocument()}
     *
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
}

