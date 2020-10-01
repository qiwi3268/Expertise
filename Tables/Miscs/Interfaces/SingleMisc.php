<?php


namespace Tables\Miscs\Interfaces;


/**
 * Интерфейс для независимых (одиночных) справочников
 *
 */
interface SingleMisc
{

    /**
     * Предназначен для получения ассоциативных массивов активных справочников
     *
     * @return array индексный массив с ассоциативными массива внутри
     */
    static public function getAllAssocWhereActive(): array;

    /**
     * Предназначен для получения ассициативного массива справочника по его id
     *
     * @param int $id id записи справочника
     * @return array|null <b>array</b> ассоциативный массив, если запись существует<br>
     * <b>null</b> в противном случае
     */
    static public function getAssocById(int $id): ?array;
}