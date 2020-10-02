<?php


namespace Tables\FinancingSources\Interfaces;


/**
 * Интерфейс для работы с источниками финансирования
 *
 */
interface FinancingSourceTable
{

    /**
     * Предназначен для получения ассоциативных массивов источников финансирования по id главного документа
     *
     * @param int $id_main_document id главного документа
     * @return array|null <b>array</b> индексный массив с ассоциативными массива внутри, если записи существуют<br>
     * <b>null</b> в противном случае
     */
    static public function getAllAssocByIdMainDocument(int $id_main_document): ?array;


    /**
     * Предназначен для удаления всех записей источников финансирования по id главного документа
     *
     * @param int $id_main_document id главного документа
     */
    static public function deleteAllByIdMainDocument(int $id_main_document): void;
}