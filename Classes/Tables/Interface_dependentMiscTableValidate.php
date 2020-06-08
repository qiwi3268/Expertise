<?php


// Интерфейс для валидации зависимых справочников
//
interface Interface_dependentMiscTableValidate{

    // Предназначен для проверки существования связи главного и зависимого справочника по их id
    //
    static public function checkExistCORRByIds(int $id_main, int $id_dependent):bool;
}