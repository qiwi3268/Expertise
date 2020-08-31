<?php


namespace Tables\Miscs\Interfaces;


// Интерфейс для валидации независимых (одиночных) справочников
//
interface SingleMiscValidate
{

    // Предназначен для проверки существования справочника по его id
    //
    static public function checkExistById(int $id): bool;
}