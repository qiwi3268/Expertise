<?php


namespace Tables\Actions\Traits;

use Lib\DataBase\SimpleQuery;
use Lib\DataBase\ParametrizedQuery;


// Трейт, частично реализующий интерфейс Tables\Actions\Interfaces\ActionTable
// Для использования трейта необходимо, чтобы перед его включением было объявлено
// статическое свойство tableName с соответствующим именем таблицы
//
trait ActionTable
{

    // Предназначен для получения ассициативных массивов дейсивий,
    // возвращает активные записи
    // возвращает данные по возрастанию столбца sort
    // Возвращает параметры-----------------------------------
    // array : ассоциативные массивы действий
    //
    static public function getAllActive(): array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `is_active`=1
                  ORDER BY `sort` ASC";
        return SimpleQuery::getFetchAssoc($query);
    }


    // Предназначен для получения ассоциативного массива активного действия по имени страницы
    // возвращает активую запись
    // Принимает параметры-----------------------------------
    // pageName string : имя страницы
    // Возвращает параметры----------------------------------
    // array : в случае, если запись существует
    // null  : в противном случае
    //
    static public function getAssocActiveByPageName(string $pageName): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `page_name`=? AND`is_active`=1";
        $result = ParametrizedQuery::getFetchAssoc($query, [$pageName]);
        return $result ? $result[0] : null;
    }

    // Предназначен для получения ассоциативного массива действия по имени страницы
    //
    static public function getAssocByPageName(string $pageName): ?array
    {
        $table = self::$tableName;

        $query = "SELECT `id`,
                         `page_name`,
                         `name`,
                         `callback_name`,
                         `description`
                  FROM `{$table}`
                  WHERE `page_name`=?";
        $result = ParametrizedQuery::getFetchAssoc($query, [$pageName]);
        return $result ? $result[0] : null;
    }
}


