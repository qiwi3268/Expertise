<?php


namespace Tables\Docs\Interfaces;


/**
 * Интерфейс для работы с дочерним документом
 *
 * Элемент этого типа имеет аттрибуте id_main_document
 *
 */
interface ChildDocument extends Document
{

    /**
     * Предназначен для проверки существования записи по id главного документа и id стадии
     *
     * @param int $id_main_document id главного документа
     * @param int $id_stage id стадии
     * @return bool
     */
    static public function checkExistByIdMainDocumentAndIdStage(int $id_main_document, int $id_stage): bool;


    /**
     * Предназначен для получения количества записей по id главного документа и id стадии
     *
     * @param int $id_main_document id главного документа
     * @param int $id_stage id стадии
     * @return int количество записей
     */
    static public function getCountByIdMainDocumentAndIdStage(int $id_main_document, int $id_stage): int;
}