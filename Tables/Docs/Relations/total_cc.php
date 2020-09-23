<?php


namespace Tables\Docs\Relations;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;
use Exception;

use Tables\DocumentationTypeTableLocator;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'doc_total_cc'</i>
 *
 * Отношения таблицы документа сводное замечание / заявление с дочерними
 * и родильскими элементами
 *
 */
final class total_cc
{

    /**
     * Предназначен для получения id сводного замечания / заключения по id заявления
     *
     * Метод документа как дочернего
     *
     * @param int $id_main_document
     * @return int|null <b>int</b> id сводного замечания / заключения по id заявления, если оно существует<br>
     * <b>null</b> сводное замечание / заключение не существует
     * @throws DataBaseEx
     */
    static public function getIdByIdMainDocument(int $id_main_document): ?int
    {
        $query = "SELECT `id`
				  FROM `doc_total_cc`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_main_document]);
        return $result ? $result[0] : null;
    }


    /**
     * Предназначен для получения дочерних документов от сводного замечения / заключения
     *
     * Метод документа как родительского
     *
     * @param int $id_total_cc
     * @param int $id_type_of_object
     * @return array ассоциативный массив формата:<br><br>
     * <i>int</i> 'id' => id сводного замечания / заключения<br>
     * <br>
     * <i>array|null</i> 'children' => <b>array</b> если дочерние документы разделов существуют, то
     * результат вызова метода {@see \Tables\Docs\Relations\section_documentation_1::getChildrenByIds()}
     * или {@see \Tables\Docs\Relations\section_documentation_2::getChildrenByIds()} в зависимости от вида объекта<br>
     * <b>null</b> дочерних разделов не существует<br>
     *
     * @throws TablesEx
     * @throws DataBaseEx
     */
    static public function getChildrenById(int $id_total_cc, int $id_type_of_object): array
    {
        $locator = new DocumentationTypeTableLocator($id_type_of_object);

        $sectionTable = $locator->getDocsRelationsSection();

        if (!is_null($ids_section = $sectionTable::getIdsByIdMainDocument($id_total_cc))) {

            $sections = $sectionTable::getChildrenByIds($ids_section);
        } else {

            $sections = null;
        }

        return [
            'id'       => $id_total_cc,
            'children' => $sections
        ];
    }


    /**
     * Предназначен для получения id главного документа (заявления)
     *
     * @param int $id
     * @return int id главного документа
     * @throws DataBaseEx
     */
    static public function getIdMainDocumentById(int $id): int
    {
        $query = "SELECT `id_main_document`
				  FROM `doc_total_cc`
                  WHERE `id`=?";
        return ParametrizedQuery::getSimpleArray($query, [$id])[0];
    }
}