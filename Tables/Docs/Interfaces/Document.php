<?php


namespace Tables\Docs\Interfaces;


/**
 * Общий интерфейс для работы с докментом
 *
 */
interface Document
{

    /**
     * Предназначен для получения названия текущей стадии по id документа
     *
     * @param int $id id документа
     * @return string название стадии
     */
    static public function getNameStageById(int $id): string;
}