<?php


namespace Tables\Responsible\type_4;

use Tables\Responsible\Interfaces\Responsible;
use Lib\DataBase\ParametrizedQuery;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Ответственные пользователи к сводному замечанию / заключению
 *
 */
final class total_cc implements Responsible
{

    static private string $tableName = 'resp_total_cc_type_4';

    static public function create(int $id_main_document, int $id_user): int
    {

    }

    static public function getResponsibleByIdMainDocument(int $id_main_document): ?array
    {
        // TODO: Implement getResponsibleByIdMainDocument() method.
    }

    static public function deleteResponsibleByIdMainDocument(int $id_main_document): void
    {
        // TODO: Implement deleteResponsibleByIdMainDocument() method.
    }
}