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
     * @param int $id id записи
     * @return string тип ответственных
     */
    static public function getResponsibleTypeById(int $id): string;


    /**
     * Предназначен для обновления текущего типа ответственных
     *
     * @param int $id id записи
     * @param string $responsible_type тип ответственных
     */
    static public function updateResponsibleTypeById(int $id, string $responsible_type): void;
}