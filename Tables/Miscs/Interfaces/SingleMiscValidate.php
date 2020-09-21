<?php


namespace Tables\Miscs\Interfaces;


/**
 * Интерфейс для валидации независимых (одиночных) справочников
 *
 */
interface SingleMiscValidate
{

    /**
     * Предназначен для проверки существования записи справочника по его id
     *
     * @param int $id id справочника
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     */
    static public function checkExistById(int $id): bool;
}