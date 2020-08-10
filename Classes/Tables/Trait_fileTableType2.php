<?php


// Трейт, реализующий интерфейс Interface_fileTableType2
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait Trait_fileTableType2{


    // Реализация метода интерфейса
    // Предназначен для создания записи в файловой таблице
    // Принимает параметры-----------------------------------
    // id int : id записи
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_application, int $id_structure_node, string $file_name, string $hash):int {

        $table = self::$tableName;

        $query = "INSERT INTO `$table`
                    (`id`, `id_application`, `id_structure_node`, `file_name`, `hash`)
                  VALUES
                    (NULL, ?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_application, $id_structure_node, $file_name, $hash]);
    }
}