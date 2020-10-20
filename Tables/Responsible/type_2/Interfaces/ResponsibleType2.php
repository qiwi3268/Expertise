<?php


namespace Tables\Responsible\type_2\Interfaces;

use Tables\Responsible\Interfaces\Responsible;


/**
 * Интерфейс для работы с таблицами "Ответственные роли"
 *
 */
interface ResponsibleType2 extends Responsible
{

    /**
     * Предназначен для создания записи в таблице
     *
     * @param int $id_main_document id главного документа
     * @param int $id_user_info_role id роли
     * @return int id созданной записи
     */
    static public function create(int $id_main_document, int $id_user_info_role): int;
}