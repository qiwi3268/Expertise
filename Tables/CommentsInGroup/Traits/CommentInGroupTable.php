<?php


namespace Tables\CommentsInGroup\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует общие методы для таблиц документа "Комментарий" в документе "Группа"
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait CommentInGroupTable
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_group id группы
     * @param int $id_comment id замечания
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_group, int $id_comment): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_group`, `id_comment`)
                  VALUES
                     (?, ?)";
        return ParametrizedQuery::set($query, [$id_group, $id_comment]);
    }
}

