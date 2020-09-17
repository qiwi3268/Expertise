<?php


namespace Tables\Responsible\type_3\Interfaces;

use Tables\Responsible\Interfaces\Responsible;


/**
 * Интерфейс для работы с таблицами "ответственные группы заявителей к заявлению"
 *
 */
interface ResponsibleType3 extends Responsible
{
    // Предназначен для создания записи в таблице
    //
    static public function create(int $id_main_document, int $id_applicant_access_group_type): int;
}