<?php


namespace Tables\Docs\Interfaces;


/**
 * Интерфейс для работы с ответственными в таблицах документов
 *
 */
interface Responsible
{


    /**
     * Предназначен для получения текущего типа ответственных
     *
     * @param int $id_main_document id главного документа
     * @return string тип ответственных
     */
    static public function getResponsibleTypeById(int $id_main_document): string;


    /**
     * Предназначен для обновления текущего типа ответственных
     *
     * @param int $id_main_document id главного документа
     * @param string $responsible_type тип ответственных
     */
    static public function updateResponsibleTypeById(int $id_main_document, string $responsible_type): void;
}