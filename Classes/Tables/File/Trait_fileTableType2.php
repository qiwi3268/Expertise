<?php


// Трейт, реализующий интерфейс Interface_fileTableType2
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait Trait_fileTableType2{


    // Реализация метода интерфейса
    // Предназначен для создания записи в файловой таблице
    // Принимает параметры-----------------------------------
    // id_application    int : id заявления
    // id_structure_node int : id стркутурного узла, к которому принадлежит файл
    // file_name      string : настоящее имя файла
    // file_size      string : размер файла
    // hash           string : имя файла в ФС сервера
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_application, int $id_structure_node, string $file_name, string $file_size, string $hash):int {

        $table = self::$tableName;

        $query = "INSERT INTO `$table`
                    (`id`, `id_application`, `id_structure_node`, `file_name`, `file_size`, `hash`)
                  VALUES
                    (NULL, ?, ?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_application, $id_structure_node, $file_name, $file_size, $hash]);
    }
}