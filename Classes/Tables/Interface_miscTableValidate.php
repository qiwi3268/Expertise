<?php


// Интерфейс для валидации справочников
//
interface Interface_miscTableValidate{

    // Предназначен для проверки существования справочника по его id
    //
    static public function checkExistById(int $id):bool;
}