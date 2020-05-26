<?php


final class ApplicationCounterTable{


    // Предназначен для инкремента внутреннего счетчика заявлений
    //
    static public function incrementInternal():void {

        $query = "UPDATE `application_counter`
                  SET `internal`=`internal`+1";

        SimpleQuery::set($query);
    }


    // Предназначен для получения внутреннего счетчика заявлений
    // Возвращает параметры-----------------------------------
    // int : текущий счетчик
    //
    static public function getInternal():int {

        $query = "SELECT `internal`
                  FROM `application_counter`";

        return SimpleQuery::getSimpleArray($query)[0];

    }
}