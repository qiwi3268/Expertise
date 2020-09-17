<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Docs\Interfaces\Responsible}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait Responsible
{

    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Docs\Interfaces\Responsible::getResponsibleTypeById()
     * @param int $id_main_document
     * @return string
     * @throws DataBaseEx
     */
    static public function getResponsibleTypeById(int $id_main_document): string
    {
        $table = self::$tableName;

        $query = "SELECT `responsible_type`
                  FROM `{$table}`
                  WHERE `id`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id_main_document])[0];
    }


    /**
     * Реализация метода интерфейса
     *
     * @see \Tables\Docs\Interfaces\Responsible::updateResponsibleTypeById()
     * @param int $id_main_document
     * @param string $responsible_type
     * @throws DataBaseEx
     */
    static public function updateResponsibleTypeById(int $id_main_document, string $responsible_type): void
    {
        $table = self::$tableName;

        $query = "UPDATE `{$table}`
                  SET `responsible_type`=?
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$responsible_type, $id_main_document]);
    }
}

