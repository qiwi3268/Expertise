<?php


namespace Tables\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'action_application'</i>
 *
 * Действия над типом документа "Заявление"
 *
 */
final class application implements Interfaces\ActionTable
{

    static private string $tableName = 'action_application';

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
        $query = "SELECT `doc_application`.`is_saved`,
                         `doc_application`.`id_stage`
                  FROM `doc_application`
                  WHERE `doc_application`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}