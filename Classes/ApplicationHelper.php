<?php


class ApplicationHelper{


    // Предназначен для получения числового имени заявления,
    // дополняет внутренний счетчик ведущим нулем
    // Принимает параметры-----------------------------------
    // internalCounter int : внутренний счетчик заявления
    // Возвращает параметры-----------------------------------
    // string : числовое имя
    //
    static public function getInternalAppNumName(int $internalCounter):string {

        $nowDate = date('Y-m');

        if($internalCounter < 10){
            $internalCounter = str_pad($internalCounter, 2,'0', STR_PAD_LEFT);
        }

        return "$nowDate-$internalCounter";
    }



}
