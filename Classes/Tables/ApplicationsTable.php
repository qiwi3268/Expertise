<?php


final class ApplicationsTable{

    // Предназначен для создания временной записи заявления
    // Принимает параметры-----------------------------------
    // id_author         int : id текущего пользователя
    // numerical_name string : числовое имя заявления
    // Возвращает параметры-----------------------------------
    // id int : id созданной записи
    //
    static public function createTemporary(int $id_author, string $numerical_name):int {

        $query = "INSERT INTO `applications`
                    (`id`, `is_saved`, `id_author`, `numerical_name`, `date_creation`)
                  VALUES
                    (NULL, 0, ?, ?, UNIX_TIMESTAMP())";

        return ParametrizedQuery::set($query, [$id_author, $numerical_name]);
    }


    // Предназначен для получения всех несохраненных заявлений
    // Возвращает параметры-----------------------------------
    // array : несохраненные заявления
    //
    static public function getAllUnsaved():array {

        $query = "SELECT *
                  FROM `applications`
                  WHERE `is_saved`=0";

        return SimpleQuery::getFetchAssoc($query);
    }


    // todo
    // тестовый метод для работы крона
    static public function deleteFromIdsArray(array $ids){

        $condition = implode(',', $ids);

        $query = "DELETE
                  FROM `applications`
                  WHERE `id` IN ($condition)";

        SimpleQuery::set($query);
    }

}