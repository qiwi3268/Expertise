<?php


namespace Tables\Files\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Files\Interfaces\FileTableType1}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait FileTableType1
{

    /**
     * Реализация метода интерфейса
     * {@see \Tables\Files\Interfaces\FileTableType1::create()}
     *
     * @param int $id_main_document
     * @param string $file_name
     * @param int $file_size
     * @param string $hash
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        string $file_name,
        int $file_size,
        string $hash
    ): int {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id`, `id_main_document`, `file_name`, `file_size`, `hash`)
                  VALUES
                     (NULL, ?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_main_document, $file_name, $file_size, $hash]);
    }
}