<?php


namespace Tables\Files\Traits;

use Lib\DataBase\ParametrizedQuery;


// Трейт, реализующий интерфейс Tables\Files\Interfaces\FileTableType2
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait FileTableType2
{

    // Предназначен для создания записи в файловой таблице
    // Принимает параметры-----------------------------------
    // id_main_document int : id главного документа
    // id_structure_node int : id стркутурного узла, к которому принадлежит файл
    // file_name      string : настоящее имя файла
    // file_size      string : размер файла
    // hash           string : имя файла в ФС сервера
    // Возвращает параметры-----------------------------------
    // int : id созданной записи
    //
    static public function create(int $id_main_document, int $id_structure_node, string $file_name, int $file_size, string $hash): int
    {
        $table = self::$tableName;

        $query = "INSERT INTO `{$table}`
                    (`id`, `id_main_document`, `id_structure_node`, `file_name`, `file_size`, `hash`)
                  VALUES
                    (NULL, ?, ?, ?, ?, ?)";
        return ParametrizedQuery::set($query, [$id_main_document, $id_structure_node, $file_name, $file_size, $hash]);
    }
}