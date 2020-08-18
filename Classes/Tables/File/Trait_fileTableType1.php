<?php


// Трейт, реализующий интерфейс Interface_fileTableType1
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait Trait_fileTableType1{


    // Реализация метода интерфейса
    // Предназначен для создания записи в файловой таблице
    // Принимает параметры-----------------------------------
    // id_application int : id заявления
    // file_name   string : настоящее имя файла
    // file_size   string : размер файла
    // hash        string : имя файла в ФС сервера
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_application, string $file_name, int $file_size, string $hash):int {

        $table = self::$tableName;

        $query = "INSERT INTO `$table`
                    (`id`, `id_application`, `file_name`, `file_size`, `hash`)
                  VALUES
                    (NULL, ?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_application, $file_name, $file_size, $hash]);
    }
}