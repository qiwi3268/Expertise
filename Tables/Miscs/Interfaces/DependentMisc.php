<?php


namespace Tables\Miscs\Interfaces;


/**
 * Интерфейс для зависимых справочников
 *
 */
interface DependentMisc
{

    /**
     * Предназначен для получения ассоциативных массивов зависимого справочника, упакованных по id главного справочника
     *
     * @return array индексный массив (id главного справочника),
     * в элементах которого находятся ассоциативные массивы зависимого справочника
     */
    static public function getAllAssocWhereActiveCorrMain(): array;
}