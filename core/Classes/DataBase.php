<?php


class DataBase{

    static protected mysqli $mysqli;

    // Предназначен для создания объекта подключения к БД
    // В случае успеха перезаписывает статическую переменную mysqli
    // Принимает параметры-----------------------------------
    // dbName string: имя базы данных, к которой создается подключение
    //
    static public function constructDB(string $dbName){

        $allConfig = require_once('/var/www/html/core/dbConfig.php');
        $config = $allConfig[$dbName];

        self::$mysqli = new mysqli($config['host'],
                                   $config['username'],
                                   $config['password'],
                                   $config['dbname']);

        if(self::$mysqli->connect_error){
            throw new DataBaseException('Ошибка подключения к базе данных: '.self::$mysqli->connect_errno, self::$mysqli->connect_error);
        }
    }


    // Предназначен для закрытия созданного подключения к БД
    //
    static public function closeDB(){
        self::$mysqli->close();
    }


    // Предназначен для выполнения параметризованного запроса
    // Принимает параметры-----------------------------------
    // query           string : параметризованный запрос к БД
    // bindParams       array : параметры запроса
    // Возвращает параметры-----------------------------------
    // объект mysqli_result для запросов типа SELECT
    // false для любых запросов DML (INSERT, UPDATE, DELETE)
    //
    static protected function executeParametrizedQuery(string $query, array $bindParams){

        // Формирования строки с сокращенными типами, для
        // их привязки к метрам параметров в sql-выражении
        // типы параметров - s(string), i(integer), d(double)
        $bindParamsTypes = '';
        foreach($bindParams as $index => $value){

            $type = gettype($value);
            switch($type){
                case 'string':
                    $bindParamsTypes .= 's';
                    break;
                case 'integer':
                    $bindParamsTypes .= 'i';
                    break;
                case 'double':
                    $bindParamsTypes .= 'd';
                    break;
                default:
                    $message = "Переданный параметр со значением значением: $value, с индексом (в рамках перебора входного массива): $index, имеет тип: $type, и не подходит под указанные типы";
                    throw new DataBaseException($message);
                    break;
            }
        }

        // Передаваемые в функцию call_user_func_array параметры в виде индексированного массива
        // в формате: [0] -> типы параметров, далее - параметры запроса
        $arrToCallback[] = $bindParamsTypes;

        for($s = 0; $s < count($bindParams); $s++){
            $arrToCallback[] = &$bindParams[$s];
        }

        // Подготовка запроса к выполнению
        $stmt = self::$mysqli->prepare($query);

        // Ошибка в формировании параметризованного запроса
        if(!$stmt){
            throw new DataBaseException(self::$mysqli->error, self::$mysqli->errno);
        }

        // Привязка переменных к параметрам запроса
        // $stmt->bind_param(типы параметров, параметры запроса)
        call_user_func_array([$stmt, 'bind_param'], $arrToCallback);

        // Ошибка при выполнении параметризованного запроса
        if(!$stmt->execute()){

            // Возможные ошибки:
            // количество символов в переменной больше, чем в поле БД
            // NULL в запросе указан в кавычках
            // повторяющееся значение для столбца с индексом unique
            throw new DataBaseException(self::$mysqli->error, self::$mysqli->errno);
        }

        $result = $stmt->get_result();

        // Закрытие подготовленного вопроса
        $stmt->close();

        return $result;
    }


    // Предназначен для выполнения простого запроса
    // Принимает параметры-----------------------------------
    // query string : простой запрос к БД
    static protected function executeSimpleQuery(string $query){

        $result = self::$mysqli->query($query);

        // Ошибка в формировании простого запроса
        if(!$result){
            throw new DataBaseException(self::$mysqli->error, self::$mysqli->errno);
        }

        return $result;
    }
}