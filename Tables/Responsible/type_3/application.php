<?php


namespace Tables\Responsible\type_3;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Responsible\type_3\Interfaces\ResponsibleType3;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'resp_application_type_3'</i>
 *
 * Ответственные группы заявителей к заявлению
 *
 */
final class application implements ResponsibleType3
{

    static private string $tableName = 'resp_application_type_3';


    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Responsible\type_3\Interfaces\ResponsibleType3::deleteResponsibleByIdMainDocument()
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
     * @see \Tables\Responsible\type_3\Interfaces\ResponsibleType3::getResponsibleByIdMainDocument()
     * @param int $id_main_document
     * @return array|null
     * @throws DataBaseEx
     */
    public static function getResponsibleByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `applicant_access_group`.`id_applicant_access_group_type`,
                         `user`.`id` as `user_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`
                  FROM `applicant_access_group`
                  INNER JOIN `user`
                     ON `applicant_access_group`.`id_user`=`user`.`id`
                  WHERE `applicant_access_group`.`id_application`=?
                     AND `applicant_access_group`.`id_applicant_access_group_type` IN
                        (SELECT `{$table}`.`id_applicant_access_group_type`
                         FROM `{$table}`
                         WHERE `{$table}`.`id_main_document`=?)";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document, $id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Responsible\type_3\Interfaces\ResponsibleType3::create()
     * @param int $id_main_document
     * @param int $id_applicant_access_group_type
     * @return int
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_applicant_access_group_type): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_applicant_access_group_type`)
                  VALUES
                    (?, ?)";
        return ParametrizedQuery::set($query, [$id_main_document, $id_applicant_access_group_type]);
    }
}



