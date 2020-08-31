<?php


namespace Tables\Helpers;

use Lib\DataBase\SimpleQuery;


// Класс предназначен для вспомогательных методов файловых таблиц
//
class Helper
{

    // Предназначен для получения строки values формата "?, ?, ?, NULL" в зависимости от количества переданных элементов массива.
    // Если элемент null, то он удаляется из массива и в values записывается NULL
    // Принимает параметры-----------------------------------
    // &bindParams array : ссылка на массив параметров
    // Возвращает параметры----------------------------------
    // string : строка values формата "?, ?, ?, NULL"
    //
    static public function getValuesWithoutNull(array &$bindParams): string
    {
        $result = [];

        foreach ($bindParams as $key => $value) {

            if (is_null($value)) {

                $result[] = 'NULL';
                unset($bindParams[$key]);
            } else {

                $result[] = '?';
            }
        }

        // Сбрасываем индексы массива, чтобы избавиться от "дырок" удаленных элементов
        $bindParams = array_values($bindParams);

        return implode(', ', $result);
    }
}