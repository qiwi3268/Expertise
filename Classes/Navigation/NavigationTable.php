<?php


namespace Classes\Navigation;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Абстрактный класс предназначен для получения количества строк в выборке (для навигационного сайдбара)
 * и ассоциативного массива для отображения данных во view
 *
 */
abstract class NavigationTable
{

    /**
     * Предназначен для получения количество строк в выборке с динамической секцией FROM, WHERE по id пользователя
     *
     *
     * @param int $id_user id пользователя
     * @return int количество строк в выборке
     * @throws DataBaseEx
     */
    static public function getCountByIdUser(int $id_user): int
    {
        $section = static::getSection(); // Позднее статическое связывание
        $query = "SELECT COUNT(*) {$section}";

        return ParametrizedQuery::getSimpleArray($query, [$id_user])[0];
    }


    /**
     * Предназначен для получение ассоциативного массива с динамической секцией FROM, WHERE по id пользователя
     *
     * В зависимости от дочернего класса, в котором вызывается этот метод, будет передана своя уникальная секция
     *
     * @param int $id_user id пользователя
     * @param string $SORT_name название столбца для сортировки
     * @param string $SORT_type тип сортировки (ASC / DESC)
     * @param int $LIMIT_offset смещение выборки на указанное количество элементов
     * @param int $LIMIT_row_count количество строк в выборке
     * @return array|null <b>array</b> в случае, если выборка по запросу существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAssocByIdUser(
        int $id_user,
        string $SORT_name,
        string $SORT_type,
        int $LIMIT_offset,
        int $LIMIT_row_count
    ): ?array {

        $section = static::getSection();
        $query = "SELECT *
                  {$section}
                  ORDER BY `{$SORT_name}` {$SORT_type}
                  LIMIT {$LIMIT_offset}, {$LIMIT_row_count}";

        return ParametrizedQuery::getFetchAssoc($query, [$id_user]);
    }


    /**
     * Абстрактный метод для получения динамической секции FROM, WHERE
     *
     * @return string
     */
    abstract static protected function getSection(): string;
}