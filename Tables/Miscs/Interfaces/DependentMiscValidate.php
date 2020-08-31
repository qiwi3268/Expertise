<?php


namespace Tables\Miscs\Interfaces;


// Интерфейс для валидации зависимых справочников
//
interface DependentMiscValidate
{

    // Предназначен для проверки существования связи главного и зависимого справочника по их id
    //
    static public function checkExistCorrByIds(int $id_main, int $id_dependent): bool;


    // Предназначен для получения ассоциативного массива зависимого справочника, упакованного по id главного справочника
    //
    static public function getAllActiveCorrMain(): array;
}