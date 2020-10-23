<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;



/**
 * Реализует общие методы для таблиц документа "Группа"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait GroupTable
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int|null $id_attached_file id прикрепленного файла
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, ?int $id_attached_file): int
    {
        $table = self::$tableName;

        $bindParams = [$id_main_document, 1, $id_attached_file];
        $values = TableHelper::getValuesWithoutNullForInsert($bindParams);

        $query = "INSERT INTO `{$table}`
                    (`id_main_document`, `id_stage`, `id_attached_file`,`date_creation`)
                  VALUES
                    ({$values},  UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}

