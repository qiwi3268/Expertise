<?php


namespace Tables\DescriptivePart\Traits;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;
use Tables\Helpers\Helper as TableHelper;


/**
 * <b>*</b> Для использования трейта необходимо, чтобы перед его включением было объявлено
 * статическое свойство <i>tableName</i> с соответствующим именем таблицы
 *
 */
trait TEP
{

    /**
     * Предназначен для создания записи в таблице ТЭП описательной части
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @param string $indicator показатель
     * @param string $measure единица измерения
     * @param string $value значение
     * @param string|null $note примечание
     * @return int
     * @throws DataBaseEx
     */
    static public function create(
        int $id_main_document,
        int $id_author,
        string $indicator,
        string $measure,
        string $value,
        ?string $note
    ): int {

        $table = self::$tableName;

        $bindParams = [$id_main_document, $id_author, $indicator, $measure, $value, $note];
        $values = TableHelper::getValuesWithoutNull($bindParams);

        $query = "INSERT INTO `{$table}`
                     (`id_main_document`, `id_author`, `indicator`, `measure`, `value`, `note`, `date_creation`)
                  VALUES
                     ({$values}, UNIX_TIMESTAMP())";
        return ParametrizedQuery::set($query, $bindParams);
    }


    /**
     * Предназначен для получения ассоциативных массивов ТЭП'ов описательной части
     * по id главного документа
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAllAssocByIdMainDocument(int $id_main_document): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `{$table}`.`id`, 
                         `{$table}`.`id_main_document`,
                         `user`.`id` as `author_id`,
                         `user`.`last_name`,
	                     `user`.`first_name`,
                         `user`.`middle_name`,
                         `{$table}`.`indicator`,
                         `{$table}`.`measure`,
                         `{$table}`.`value`,
                         `{$table}`.`note`
                  FROM `{$table}`
                  JOIN `user`
                     ON (`{$table}`.`id_author`=`user`.`id`)
                  WHERE `{$table}`.`id_main_document`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result : null;
    }


    /**
     * Предназначен для удаления всех записей в таблице ТЭП описательной части
     * по id главного документа и id автора
     *
     * @param int $id_main_document id главного документа
     * @param int $id_author id автора
     * @throws DataBaseEx
     */
    static public function deleteAllByIdMainDocumentAndIdAuthor(int $id_main_document, int $id_author): void
    {
        $table = self::$tableName;

        $query = "DELETE
                  FROM `{$table}`
                  WHERE `id_main_document`=? AND `id_author`=?";
        ParametrizedQuery::set($query, [$id_main_document, $id_author]);
    }
}

