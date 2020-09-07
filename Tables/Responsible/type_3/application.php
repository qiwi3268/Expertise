<?php


namespace Tables\Responsible\type_3;

use Tables\Responsible\Interfaces\Responsible;
use Tables\Responsible\type_3\Interfaces\ResponsibleType3;
use Lib\DataBase\ParametrizedQuery;


// Ответственные группы заявителей к заявлению
//
final class application implements Responsible, ResponsibleType3
{

    static private string $tableName = 'resp_application_type_3';


    // Предназначен для удаления всех ответственных
    //
<<<<<<< HEAD
    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void
=======
    static public function deleteResponsible(int $id_main_document): void
>>>>>>> a079240578d525ed83df11c016d74a5d605155e8
    {
        $table = self::$tableName;

        $query = "DELETE FROM `{$table}`
                  WHERE `id_main_document`=?";
        ParametrizedQuery::set($query, [$id_main_document]);
    }


<<<<<<< HEAD
    public static function getResponsibleByIdMainDocument(int $id_main_document): ?array
=======
    public static function getResponsible(int $id_main_document): ?array
>>>>>>> a079240578d525ed83df11c016d74a5d605155e8
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


    // Предназначен для создания записи в таблице
    // Принимает параметры-----------------------------------
    // id_main_document               int : id главного документа (заявления)
    // id_applicant_access_group_type int : id из applicant_access_group_type
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
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



