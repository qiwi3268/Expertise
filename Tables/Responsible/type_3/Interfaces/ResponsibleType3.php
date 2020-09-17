<?php


namespace Tables\Responsible\type_3\Interfaces;

use Tables\Responsible\Interfaces\Responsible;


/**
 * Интерфейс для работы с таблицами "Ответственные группы заявителей"
 *
 */
interface ResponsibleType3 extends Responsible
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_applicant_access_group_type id вида группы доступа заявителей к заявлению<br>
     * Таблица: 'applicant_access_group_type'
     * @return int id созданной записи
     */
    static public function create(int $id_main_document, int $id_applicant_access_group_type): int;
}