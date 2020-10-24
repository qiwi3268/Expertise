<?php


namespace Tables\Messages\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует общие методы для таблиц ответа эксперта на ответ заявителя
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait MessageExpertTable
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_expert id эксперта
     * @param string $answer ответ на ответ заявителя
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(int $id_main_document, int $id_expert, string $answer): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_expert`, `answer`, `date_creation`)
                  VALUES
                     (?, ?, ?, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, [$id_main_document, $id_expert, $answer]);
    }
}
