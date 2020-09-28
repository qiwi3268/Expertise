<?php


namespace Tables\Actions;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'action_section_documentation_1'</i>
 *
 * Действия над типом документа "Раздел"
 * Для вида объекта "Производственные / непроизводственные"
 *
 */
final class section_documentation_1 implements Interfaces\ActionTable
{

    static private string $tableName = 'action_section_documentation_1';

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
        $query = "SELECT `doc_section_documentation_1`.`id_stage`
                  FROM `doc_section_documentation_1`
                  WHERE `doc_section_documentation_1`.`id`=?";
        return ParametrizedQuery::getFetchAssoc($query, [$id])[0];
    }
}