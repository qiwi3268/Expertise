<?php


namespace Tables\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'action_section_documentation_2'</i>
 *
 * Действия над типом документа "Раздел"
 * Для вида объекта "Линейные"
 *
 */
final class section_documentation_2 implements Interfaces\ActionTable
{

    static private string $tableName = 'action_section_documentation_2';

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
        $query = "SELECT `doc_section_documentation_2`.`id_stage`
                  FROM `doc_section_documentation_2`
                  WHERE `doc_section_documentation_2`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}