<?php


namespace Tables\FinancingSources\Interfaces;


/**
 * Интерфейс для работы с источниками финансирования
 *
 */
interface FinancingSourceTable
{

    /**
     * Предназначен для получения ассоциативных массивов источников финансирования по id заявления
     *
     * @param int $id_application id заявления
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocByIdApplication(int $id_application): ?array;
}