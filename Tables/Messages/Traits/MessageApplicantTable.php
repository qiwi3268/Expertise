<?php


namespace Tables\Messages\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;

use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * Реализует общие методы для таблиц ответа заявителя на замечания
 *
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением были объявлены
 * статические свойства:
 * - tableName с соответствующим именем таблицы
 *
 */
trait MessageApplicantTable
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_applicant id заявителя
     * @param string $answer ответ на замечание
     * @param string $file_changes изменения в файле
     * @param int|null $id_new_file id замененного файла
     * @return int id созданной записи
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_applicant,
        string $answer,
        string $file_changes,
        ?int $id_new_file
    ): int {

        $table = self::$tableName;

        $bindParams = [$id_main_document, $id_applicant, $answer, $file_changes, $id_new_file];
        $values = TableHelper::getValuesWithoutNullForInsert($bindParams);

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_applicant`, `answer`, `file_changes`, `id_new_file`, `date_creation`)
                  VALUES
                     ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }
}
