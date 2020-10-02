<?php


namespace Tables;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\ParametrizedQuery;


/**
 * Таблица: <i>'common_part_applicant_details'</i>
 *
 */
final class common_part_applicant_details
{

    /**
     * Предназначен для получения ассоциативного массива сведений о заявителе из общей части
     * по id сводного замечаничя / заключения
     *
     * @param int $id_main_document id сводного замечания / заключения
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     * @throws DataBaseEx
     */
    static public function getAssocByIdTotalCC(int $id_main_document): ?array
    {
        $query = "SELECT *
                  FROM `common_part_applicant_details`
                  WHERE `id_main_document`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id_main_document]);
        return $result ? $result[0] : null;
    }
}