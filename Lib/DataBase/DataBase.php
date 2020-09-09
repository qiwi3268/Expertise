<?php


namespace Lib\DataBase;

use Lib\Exceptions\DataBase as SelfEx;
use Exception;
use mysqli;


class DataBase
{

    static protected mysqli $mysqli;


    // Предназначен для создания объекта подключения к БД
    // В случае успеха перезаписывает статическую переменную mysqli
    // Принимает параметры-----------------------------------
    // dbName string: имя базы данных, к которой создается подключение
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\DataBase:
    //   ошибка подключения к базе данных
    //
    static public function constructDB(string $dbName)
    {

        $allConfig = require_once('/var/www/dbConfig.php');
        $config = $allConfig[$dbName];

        self::$mysqli = new mysqli(
            $config['host'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );

        if (self::$mysqli->connect_error) {
            throw new SelfEx('Ошибка подключения к базе данных: ' . self::$mysqli->connect_errno, self::$mysqli->connect_error);
        }
    }


    // Предназначен для закрытия созданного подключения к БД
    //
    static public function closeDB()
    {
        self::$mysqli->close();
    }


    // Предназначен для выполнения параметризованного запроса
    // Принимает параметры-----------------------------------
    // query     string : параметризованный запрос к БД
    // bindParams array : параметры запроса
    // Возвращает параметры-----------------------------------
    // объект mysqli_result для запросов типа SELECT
    // false для любых запросов DML (INSERT, UPDATE, DELETE)
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\DataBase:
    //   1 - переданный параметр не подходит под указанные типы
    //   ошибка в формировании параметризованного запроса
    //   ошибка при привязке привязке переменных
    //   ошибка при выполнении параметризованного запроса
    //   ошибка при получении результата параметризованного запроса
    //   ошибка при закрытии параметризованного запроса
    //
    static protected function executeParametrizedQuery(string $query, array $bindParams)
    {
        // Формирования строки с сокращенными типами, для
        // их привязки к метрам параметров в sql-выражении
        // типы параметров - s(string), i(integer), d(double)

        // Сбрасываем индексы массива, чтобы они наверняка начинались с 0 и шли по порядку
        $bindParams = array_values($bindParams);

        $bindParamsTypes = '';
        foreach ($bindParams as $index => $value) {

            $type = gettype($value);
            switch ($type) {
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
                    $message = "Переданный параметр со значением значением: '{$value}', с индексом (в рамках перебора входного массива): '{$index}', имеет тип: '{$type}', и не подходит под указанные типы";
                    throw new SelfEx($message, 1);
            }
        }

        // Передаваемые в функцию call_user_func_array параметры в виде индексного массива
        // в формате: [0] -> типы параметров, далее - ссылки на параметры запроса
        $arrToCallback[] = $bindParamsTypes;

        for ($l = 0; $l < count($bindParams); $l++) {
            $arrToCallback[] = &$bindParams[$l];
        }

        $stmt = self::$mysqli->prepare($query);

        // Подготовка запроса к выполнению
        if ($stmt === false) {
            // Ошибка в формировании параметризованного запроса
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        // Привязка переменных к параметрам запроса
        // $stmt->bind_param(типы параметров, параметры запроса)
        if(
            call_user_func_array([$stmt, 'bind_param'], $arrToCallback) === false
            && self::$mysqli->errno != 0
        ){
            // Ошибка при привязке привязке переменных
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        if ($stmt->execute() === false) {
            // Ошибка при выполнении параметризованного запроса
            // Возможные ошибки:
            // количество символов в переменной больше, чем в поле БД
            // NULL в запросе указан в кавычках
            // повторяющееся значение для столбца с индексом unique
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        $result = $stmt->get_result();

        if(
            $result === false
            && self::$mysqli->errno != 0
        ){
            // Ошибка при получении результата параметризованного запроса
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        // Закрытие подготовленного вопроса
        if($stmt->close() === false){
            // Ошибка при закрытии параметризованного запроса
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        return $result;
    }


    // Предназначен для выполнения простого запроса
    // Принимает параметры-----------------------------------
    // query string : простой запрос к БД
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\DataBase:
    //   ошибка при выполнении простого запроса
    //
    static protected function executeSimpleQuery(string $query)
    {
        $result = self::$mysqli->query($query);

        // Ошибка при выполнении простого запроса
        if ($result === false) {
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        return $result;
    }


    // Предназначен для выполнения транзакции
    // Принимает параметры-----------------------------------
    // Transaction Transaction : экземпляр класса, содержащий в себе запросы,
    // которые необходимо выполнить в рамках транзакции
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\DataBase:
    //   ошибка при старте транзакции
    //   ошибка при откате текущей транзакции
    //   ошибка при фиксации текущей транзакции
    //
    static protected function executeTransaction(Transaction $transaction): void
    {
        if (self::$mysqli->begin_transaction() === false) {
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }

        try {

            $transaction->executeQueries();

        } catch (SelfEx $e) {

            if (self::$mysqli->rollback() === false) {
                throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
            }
            throw new SelfEx($e->getMessage(), $e->getCode());
        } catch (Exception $e) {

            if (self::$mysqli->rollback() === false) {
                throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
            }
            throw new Exception($e->getMessage(), $e->getCode());
        }

        if (self::$mysqli->commit() === false) {
            throw new SelfEx(self::$mysqli->error, self::$mysqli->errno);
        }
    }
}