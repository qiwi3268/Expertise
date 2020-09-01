<?php


namespace Tables;

use Lib\DataBase\ParametrizedQuery;


final class expertise_subject
{

    // Предназначен для создания записи Предмета экспертизы к заявлению
    // Принимает параметры-----------------------------------
    // id_application       int : id заявления
    // id_expertise_subject int : id Предмета экспертизы из справочника
    //
    static public function create(int $id_application, int $id_expertise_subject): void
    {
        $query = "INSERT INTO `expertise_subject`
                    (`id_application`, `id_expertise_subject`)
                  VALUES
                    (?, ?)";
        ParametrizedQuery::set($query, [$id_application, $id_expertise_subject]);
    }


    // Предназначен для получения простого массива id Предметов экспертизы,
    // принадлежащим заявлению
    // Принимает параметры-----------------------------------
    // id_application  int : id заявления
    // Возвращает параметры----------------------------------
    // array : в случае, если к заявлению прикреплек Предмет
    // null  : в противном случае
    //
    static public function getIdsByIdApplication(int $id_application): ?array
    {
        $query = "SELECT `id_expertise_subject` AS `id`
                  FROM `expertise_subject`
                  WHERE `id_application`=?";
        $result = ParametrizedQuery::getSimpleArray($query, [$id_application]);
        return $result ? $result : null;
    }


    // Предназначен для удаления записи Предмета экспертизы к заявлению
    // Принимает параметры-----------------------------------
    // id_application       int : id заявления
    // id_expertise_subject int : id Предмета экспертизы из справочника
    //
    static public function delete(int $id_application, int $id_expertise_subject): void
    {
        $query = "DELETE
                  FROM `expertise_subject`
                  WHERE `id_application`=? AND `id_expertise_subject`=?";
        ParametrizedQuery::set($query, [$id_application, $id_expertise_subject]);
    }
}