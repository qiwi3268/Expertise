<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\SimpleQuery;


/**
 * Таблица: <i>'application_counter'</i>
 *
 */
final class application_counter
{

    /**
     * Предназначен для инкремента внутреннего счетчика заявлений
     *
     * @throws DataBaseEx
     */
    static public function incrementInternal(): void
    {
        $query = "UPDATE `application_counter`
                  SET `internal`=`internal`+1";
        SimpleQuery::set($query);
    }


    /**
     * Предназначен для получения внутреннего счетчика заявлений
     *
     * @return int текущий счетчик
     * @throws DataBaseEx
     */
    static public function getInternal(): int
    {
        $query = "SELECT `internal`
                  FROM `application_counter`";
        return SimpleQuery::getSimpleArray($query)[0];
    }
}