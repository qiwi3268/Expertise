<?php


// Трейт, реализующий интерфейс Interface_dependentMiscTableValidate
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство CORRtableName с именем таблицы корреляции с главным справочником
//
trait Trait_dependentMiscTableValidate{

    // Реализация метода интерфейса
    // Предназначен для проверки существования связи главного и зависимого справочника по их id
    // Принимает параметры-----------------------------------
    // id_main      int : id главного справочника
    // id_dependent int : id зависимого справочника
    // Возвращает параметры----------------------------------
    // true   : зависимость существует
    // false  : зависимость не существует
    //
    static public function checkExistCORRByIds(int $id_main, int $id_dependent):bool {

        $table = self::$CORRtableName;

        $query = "SELECT count(*)>0
                  FROM `$table`
                  WHERE `id_main`=? AND `id_dependent`=?";

        // Автоматическое преобразование к bool типу
        return ParametrizedQuery::getSimpleArray($query, [$id_main, $id_dependent])[0];
    }
}