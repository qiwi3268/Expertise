<?php


namespace Lib\DataBase;

use Lib\Exceptions\DataBase as SelfEx;
use Exception;
use mysqli;
use mysqli_result;


/**
 * Предназначен для работы с mysqli базой данных
 *
 */
class DataBase
{

    static protected mysqli $mysqli;


    /**
     * Предназначен для создания объекта подключения к БД
     *
     * В случае успеха перезаписывает статическую переменную <i>mysqli</i>
     *
     * @param string $dbName имя базы данных, к которой создается подключение
     * @throws SelfEx
     */
    static public function constructDB(string $dbName): void
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

        // Преобразовывает столбцы типов integer и float к числам
        // Работает только с установленным расширением mysqlnd
        if (self::$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true) === false) {
            throw new SelfEx('Ошибка установки настройки MYSQLI_OPT_INT_AND_FLOAT_NATIVE: ' . self::$mysqli->error, self::$mysqli->errno);
        }
    }


    /**
     * Предназначен для закрытия созданного подключения к БД
     *
     * @throws SelfEx
     */
    static public function closeDB(): void
    {
        if (self::$mysqli->close() === false) {
            throw new SelfEx('Ошибка при закрытии созданного подключения к БД: ' . self::$mysqli->connect_errno, self::$mysqli->connect_error);
        }
    }


    /**
     * Предназначен для выполнения параметризованного запроса
     *
     * @param string $query параметризованный запрос к БД
     * @param array $bindParams параметры запроса
     * @return false|mysqli_result <b>false</b> для любых запросов DML (INSERT, UPDATE, DELETE)<br>
     * <b>mysqli_result</b> для запросов типа SELECT
     * @throws SelfEx
     */
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
                case 'string' :
                    $bindParamsTypes .= 's';
                    break;
                case 'integer' :
                    $bindParamsTypes .= 'i';
                    break;
                case 'double' :
                    $bindParamsTypes .= 'd';
                    break;
                default :
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
            throw new SelfEx('Ошибка в формировании параметризованного запроса:' . self::$mysqli->error, self::$mysqli->errno);
        }

        // Привязка переменных к параметрам запроса
        // $stmt->bind_param(типы параметров, параметры запроса)
        if(
            call_user_func_array([$stmt, 'bind_param'], $arrToCallback) === false
            && self::$mysqli->errno != 0
        ){
            throw new SelfEx('Ошибка при привязке привязке переменных:' . self::$mysqli->error, self::$mysqli->errno);
        }

        if ($stmt->execute() === false) {
            // Возможные ошибки:
            // количество символов в переменной больше, чем в поле БД
            // NULL в запросе указан в кавычках
            // повторяющееся значение для столбца с индексом unique
            throw new SelfEx('Ошибка при выполнении параметризованного запроса: ' . self::$mysqli->error, self::$mysqli->errno);
        }

        $result = $stmt->get_result();

        if(
            $result === false
            && self::$mysqli->errno != 0
        ){
            throw new SelfEx('Ошибка при получении результата параметризованного запроса: ' . self::$mysqli->error, self::$mysqli->errno);
        }

        // Закрытие подготовленного вопроса
        if($stmt->close() === false){
            throw new SelfEx('Ошибка при закрытии параметризованного запроса: ' . self::$mysqli->error, self::$mysqli->errno);
        }

        return $result;
    }


    /**
     * Предназначен для выполнения простого запроса
     *
     * @param string $query простой запрос к БД
     * @return true|mysqli_result <b>mysqli_result</b> для запросов типа SELECT, SHOW, DESCRIBE или EXPLAIN<br>
     * <b>true</b> для остальных успешных запросов
     * @throws SelfEx
     */
    static protected function executeSimpleQuery(string $query)
    {
        $result = self::$mysqli->query($query);

        if ($result === false) {
            throw new SelfEx('Ошибка при выполнении простого запроса:' . self::$mysqli->error, self::$mysqli->errno);
        }

        return $result;
    }


    /**
     * Предназначен для выполнения транзакции
     *
     * @param Transaction $transaction экземпляр класса, содержащий в себе запросы,
     * которые необходимо выполнить в рамках транзакции
     * @throws SelfEx
     * @throws Exception
     */
    static protected function executeTransaction(Transaction $transaction): void
    {
        if (self::$mysqli->begin_transaction() === false) {

            throw new SelfEx('Ошибка при старте транзакции: ' . self::$mysqli->error, self::$mysqli->errno);
        }

        try {

            $transaction->executeQueries();
        } catch (SelfEx $e) {

            if (self::$mysqli->rollback() === false) {

                throw new SelfEx('Ошибка при откате текущей транзакции: ' . self::$mysqli->error, self::$mysqli->errno);
            }
            throw new SelfEx($e->getMessage(), $e->getCode());
        } catch (Exception $e) {

            if (self::$mysqli->rollback() === false) {

                throw new SelfEx('Ошибка при откате текущей транзакции: ' . self::$mysqli->error, self::$mysqli->errno);
            }
            throw new Exception($e->getMessage(), $e->getCode());
        }
        if (self::$mysqli->commit() === false) {

            throw new SelfEx('Ошибка при фиксации текущей транзакции: ' . self::$mysqli->error, self::$mysqli->errno);
        }
    }
}