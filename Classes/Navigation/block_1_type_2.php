<?php


<<<<<<< HEAD
// block1  - "Мои документы" - для Заявителя
// type2   - "Мои заявления" - сохранено,
//                             автор - пользователь //todo в зависимости от ролей в заявлении дальше добавлять


class block_1_type_2 extends NavigationTable{

    static protected function getSection():string {
        
        $section = "FROM `applications`
                    WHERE `is_saved`='0' AND `applications`.`id_author`=?";
        return $section;
    }
=======
class block_1_type_2 extends NavigationTable{
    
>>>>>>> 8e8d264d1195883c5becaf209e294f9ff5296ccb
}
