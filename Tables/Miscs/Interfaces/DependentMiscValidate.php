<?php


namespace Tables\Miscs\Interfaces;


/**
 * Интерфейс для валидации зависимых справочников
 *
 */
interface DependentMiscValidate
{

    /**
     * Предназначен для проверки существования связи главного и зависимого справочника по их id
     *
     * @param int $id_main id главного справочника
     * @param int $id_dependent id зависимого справочника
     * @return bool <b>true</b> зависимость существует<br>
     * <b>false</b> зависимость не существует
     */
    static public function checkExistCorrByIds(int $id_main, int $id_dependent): bool;
}