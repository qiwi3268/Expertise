<?php


namespace Tables\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'action_total_cc'</i>
 *
 * Действия над типом документа "Сводное замечание / заключение"
 *
 */
class total_cc implements Interfaces\ActionTable
{

    static private string $tableName = 'action_total_cc';

    use Traits\ActionTable;


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Actions\Interfaces\ActionTable::getAssocBusinessProcessById()}
     *
     * @param int $id
     * @return array
     * @throws DataBaseEx
     */
    static public function getAssocBusinessProcessById(int $id): array
    {
        $query = "SELECT `doc_total_cc`.`id_stage`
                  FROM `doc_total_cc`
                  WHERE `doc_total_cc`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}