<?php


namespace Tables\CommonInterfaces;


/**
 * Интерфейс для gля проверки существования записи документа по его id
 *
 */
interface Existent
{

    /**
     * Предназначен для проверки существования записи по её id
     *
     * @param int $id id записи
     * @return bool <b>true</b> запись существует<br>
     * <b>false</b> в противном случае
     */
    static public function checkExistById(int $id): bool;
}