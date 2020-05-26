<?php


// Трейт, реализующий интерфейс Interface_fileTableType1
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait Trait_fileTableType1{


    // Реализация метода интерфейса
    // Предназначен для создания записи в файловой таблице
    // Принимает параметры-----------------------------------
    // id int : id записи
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_application, string $file_name, string $hash):int {

        $table = self::$tableName;

        $query = "INSERT INTO `$table`
                    (`id`, `id_application`, `file_name`, `hash`, `is_uploaded`)
                  VALUES
                    (NULL, ?, ?, ?, '0')";
        return ParametrizedQuery::set($query, [$id_application, $file_name, $hash]);
    }


    // Реализация метода интерфейса
    // Предназначен для удаления записи из файловой таблицы
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    static public function deleteById(int $id):void {

        $table = self::$tableName;

        $query = "DELETE
                  FROM `$table`
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Реализация метода интерфейса
    // Предназначен для установки флага загрузки файла на сервер
    // Принимает параметры-----------------------------------
    // id int : id записи
    //
    public static function setUploadedById(int $id):void {

        $table = self::$tableName;

        $query = "UPDATE `$table`
                  SET `is_uploaded`='1'
                  WHERE `id`=?";
        ParametrizedQuery::set($query, [$id]);
    }


    // Реализация метода интерфейса
    // Предназначен для получения ассоциативного массива записи по id
    // Принимает параметры-----------------------------------
    // id int : id записи
    // Возвращает параметры-----------------------------------
    // array : в случае, если запись существует
    // null  : в противном случае
    //
    public static function getAssocById(int $id):?array {

        $table = self::$tableName;

        $query = "SELECT *
                  FROM `$table`
                  where `id`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$id]);
        return $result ? $result[0] : null;
    }
}