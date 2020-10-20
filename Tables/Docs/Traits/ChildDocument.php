<?php


namespace Tables\Docs\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Реализует интерфейс {@see \Tables\Docs\Interfaces\ChildDocument}
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait ChildDocument
{

    use Document;


    /**
     * Реализация метода интерфейса
     * {@see \Tables\Docs\Interfaces\ChildDocument::checkExistByIdMainDocumentAndIdStage()}
     *
     * @param int $id_main_document
     * @param int $id_stage
     * @return bool
     * @throws DataBaseEx
     */
    static public function checkExistByIdMainDocumentAndIdStage(int $id_main_document, int $id_stage): bool
    {
        $table = self::$tableName;

        $query = "SELECT COUNT(*)>0
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_stage`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main_document, $id_stage])[0];
    }
}

