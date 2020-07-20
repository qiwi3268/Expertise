<?php


class ApplicationHelper{


    // Предназначен для получения числового имени заявления,
    // дополняет внутренний счетчик ведущим нулем
    // Принимает параметры-----------------------------------
    // internalCounter int : внутренний счетчик заявления
    // Возвращает параметры----------------------------------
    // string : числовое имя
    //
    static public function getInternalAppNumName(int $internalCounter):string {

        $nowDate = date('Y-m');

        if($internalCounter < 10){
            $internalCounter = str_pad($internalCounter, 2,'0', STR_PAD_LEFT);
        }

        return "$nowDate-$internalCounter";
    }



    // Предназначен для проверки прав заявителя на сохранение заявления
    // Принимает параметры-----------------------------------
    // applicationId int : id заявления
    // Возвращает параметры----------------------------------
    // true  : заявитель имеет право на сохранение заявления
    // false : не имеет
    //
    // todo со временем расширять права, чтобы не только автор мог сохранять
    static public function checkApplicantRightsToSaveApplication(int $applicationId):bool {

        $ids = Session::getAuthorRoleApplicationIds();

        if(!is_null($ids) && in_array($applicationId, $ids, true)){
            return true;
        }
        return false;
    }

    static public function getPaginationDependentMisc(array $dependentMisc, int $paginationSize):array {

        foreach($dependentMisc as &$misc){
            $misc = array_chunk($misc, $paginationSize);
        }
        unset($misc);

        return  $dependentMisc;
    }






}
