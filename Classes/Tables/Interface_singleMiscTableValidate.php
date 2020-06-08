<?php


// Интерфейс для валидации независимых справочников
//
interface Interface_singleMiscTableValidate{

    // Предназначен для проверки существования справочника по его id
    //
    static public function checkExistById(int $id):bool;
}