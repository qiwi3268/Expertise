<?php


namespace Tables\Docs\Relations;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;

/**
 * Таблица: <i>'doc_section_documentation_1'</i>
 *
 * Отношения таблицы документа раздела с дочерними и
 * родительскими элементами
 *
 */
final class section_documentation_1
{

    /**
     * Предназначен для получения индексного массива с id разделов по id сводного замечания / заключения
     *
     * Метод документа как дочернего
     *
     * @param int $id_main_document
     * @return array|null <b>array</b> индексный массив с id разделов по id сводного замечания / заключения, если они существуют<br>
     * <b>null</b> разделы не существуют
     * @throws DataBaseEx
     */
    static public function getIdsByIdMainDocument(int $id_main_document): ?array
    {
        $query = "SELECT `id`
				  FROM `doc_section_documentation_1`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document]);
        return $result ? $result : null;
    }

    // Метод документа как родительского
    // todo documentation
    static public function getChildrenByIds(array $ids_section): array
    {
        $children = [];
        //todo обращение к документам замечаний
        foreach ($ids_section as $id) {

            $children[] = [
                'id'       => $id,
                'children' => null,
                ];
        }
        return $children;
    }


    /**
     * Предназначен для получения id главного документа (сводного замечания / заключения)
     *
     * @param int $id
     * @return int id главного документа
     * @throws DataBaseEx
     */
    static public function getIdMainDocumentById(int $id): int
    {
        $query = "SELECT `id_main_document`
				  FROM `doc_section_documentation_1`
                  WHERE `id`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }
}