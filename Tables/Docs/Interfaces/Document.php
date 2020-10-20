<?php


namespace Tables\Docs\Interfaces;


/**
 * Общий интерфейс для работы с документом
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


    /**
     * Предназначен для обновления текущей стадии по id документа
     *
     * @param int $id_stage id стадии
     * @param int $id id документа
     */
    static public function updateIdStageById(int $id_stage, int $id): void;
}