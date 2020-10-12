<?php


namespace Tables\Helpers;
use Tables\Exceptions\Tables as SelfEx;


/**
 * Предназначен для вспомогательных табличных методов
 *
 */
class Helper
{

    /**
     * Предназначен для получения строки VALUES формата "?, ?, ?, NULL"
     * в зависимости от количества переданных элементов массива
     *
     * Если элемент null, то он удаляется из массива и в values записывается NULL
     *
     * @param array $bindParams ссылка на массив параметров
     * @return string строка values формата "?, ?, ?, NULL"
     */
    static public function getValuesWithoutNullForInsert(array &$bindParams): string
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
        return implode(', ', $result);
    }

    /**
     * Предназначен для получения строки SET формата "`param1`=?, `param2`=NULL, `param3`=?"
     * и массива bindParams
     *
     * Все значения записываются в строку 'SETPart' в формате '?' или 'NULL'.<br>
     * В массив 'bindParams' добавляются только те значения, которые не NULL
     *
     * @param array $array ассоциативный массив, где ключ - название столбца в БД,
     * а значение - величина, которая может быть null
     * @return array ассоциативный массив формата:<br>
     * 'SETPart' => "`param1`=?, `param2`=NULL, `param3`=?"<br>
     * 'bindParams' => [1, 2]
     */
    static public function getValuesWithoutNullForUpdate(array $array): array
    {
        $SETPart = [];
        $bindParams = [];

        foreach ($array as $key => $value) {

            if (is_null($value)) {
                $SETPart[] = "`{$key}`=NULL";
            } else {
                $SETPart[] = "`{$key}`=?";
                $bindParams[] = $value;
            }
        }
        return [
            'SETPart'    => implode(', ', $SETPart),
            'bindParams' => $bindParams
        ];
    }


    /**
     * Предназначен для реструктуризации данных справочника в отдельный подмассив
     *
     * Перекладывает полученные данные о справочнике в отдельный подмассив<br>
     * В случае, если данные null, то новое свойство также null<br>
     * Полученные данные id_misc и name_misc вырезаются из массива
     *
     * @param array $result <i>ссылка</i> на результат из БД
     * @param string $id_misc id справочника из запроса в БД
     * @param string $name_misc имя справочника из запроса в БД
     * @param string $restructuredName имя нового свойства, в которое будет записаны 'id' и 'name'
     * @throws SelfEx
     */
    static public function restructureMiscToSubarray(
        array &$result,
        string $id_misc,
        string $name_misc,
        string $restructuredName
    ): void {

        if (!array_key_exists($id_misc, $result) || !array_key_exists($name_misc, $result)) {
            throw new SelfEx("В массиве result отсутствует(ют) свойства: '{$id_misc}' и / или '{$name_misc}'", 1001);
        }

        if (is_null($result[$id_misc])) {

            $result[$restructuredName] = null;
        } else {

            $result[$restructuredName] = [
                'id'   => $result[$id_misc],
                'name' => $result[$name_misc]
            ];

            $result[$restructuredName]['name'] = $result[$name_misc];
        }
        unset($result[$id_misc], $result[$name_misc]);
    }
}