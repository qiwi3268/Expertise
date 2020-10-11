<?php


namespace Tables\Comment\VersionFiles\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует общие методы для таблиц версий файлов к замечанию
 * 
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait VersionFileTable
{

    /**
     * Предназначен для создания записи в таблице версий файлов к замечанию
     *
     * @param int $id_previous_file id предыдущей версии файла
     * @param int $id_next_file id следующей версии файла
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_previous_file,
        int $id_next_file
    ): int {

        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_previous_file`, `id_next_file`)
                  VALUES (?, ?)";
        return ParametrizedQuery::set($query, [$id_previous_file, $id_next_file]);
    }
}