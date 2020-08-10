<?php


//todo по мере заполнения разбивать текущий класс на меньшие
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
    
    
    // Предназначен для получения дефолтных GET-параметров для навигационной страницы
    //
    static public function getDefaultNavigationPage():array {
    
        $roles = Session::getUserRoles();
<<<<<<< HEAD
        
        if(in_array(_ROLE['APP'], $roles)) return ['b' => 'block_2', 'v' => 'view_2'];
=======
    
        if(in_array(_ROLE['APP'], $roles)) return ['b' => 'block_1', 'v' => 'view_1'];
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
    }



    // Предназначен для проверки прав заявителя на сохранение заявления
    // Принимает параметры-----------------------------------
    // applicationId int : id заявления
    // Возвращает параметры----------------------------------
    // true  : заявитель имеет право на сохранение заявления
    // false : не имеет
    //
    // todo со временем расширять права, чтобы не только автор мог сохранять
    // todo перенести это дело в специализированный класс
    // todo тут необходимо проверять стадии заявления на возможность сохранять (тут значтит надо будет вызвать коллбек, ответственный за действие "редактировать заявление")
    static public function checkApplicantRightsToSaveApplication(int $applicationId):bool {

        $ids = Session::getAuthorRoleApplicationIds();

        if(!is_null($ids) && in_array($applicationId, $ids, true)){
            return true;
        }
        return false;
    }
}
