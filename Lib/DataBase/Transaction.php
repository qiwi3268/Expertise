<?php


namespace Lib\DataBase;

use Lib\Exceptions\Transaction as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use ReflectionException;
use ReflectionMethod;


/**
 * Предназначен для выполнения транзакций к базе данных
 *
 */
final class Transaction extends DataBase
{

    /**
     * Массив добавленных запросов
     *
     */
    private array $queries = [];

    /**
     * Результаты, передающися по цепочке вызовов
     *
     */
    private array $chainResults = [];

    /**
     * Ассоциативный <b>массив_1</b> результатов последней транзации формата:
     *
     * <i>Ключ_1</i> - название класса<br>
     * <i>Значение_1</i> - ассоциавтивный <b>массив_2</b> формата:<br>
     * <i>Ключ_2</i> - название метода<br>
     * <i>Значение_2</i> - возвращенный методом результат
     *
     */
    private array $lastResults = [];


    /**
     * Предназначен для добавления запроса в список транзакций
     *
     * @param mixed $class экземпляр объекта или имя класса
     * @param string $method название метода класса
     * @param array $params массив параметров, каждый из которых будет передан как аргумент метода method
     * @param string|null $setterKey <b>string</b> результат вызова текущего метода будет записан в значение по ключу setterKey, передающееся по цепочке<br>
     * <b>null</b> не будет записан
     * @param string|null $getterKey <b>string</b> результат вызова предыдущего метода по ключу getterKey,<br>
     * будет передан как первый аргумент в текущий метод<br>
     * <b>null</b> не будет передан
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function add(
        $class,
        string $method,
        array $params = [],
        ?string $setterKey = null,
        ?string $getterKey = null
    ): void {

        if (is_object($class)) {

            $className = '\\' . get_class($class);
        } else {
            if (!class_exists($class)) {
                throw new SelfEx("Переданный класс: '{$class}' не существует", 1);
            }
            $className = $class;
        }


        if (!method_exists($class, $method)) {
            throw new SelfEx("Переданный метод: '{$className}:{$method}' не существует", 2);
        }

        $reflection = new ReflectionMethod($class, $method);
        $min = $reflection->getNumberOfRequiredParameters();
        $max = $reflection->getNumberOfParameters();

        // Если метод принимает результат из цепочки, то в него должно быть передано на 1 параметр меньше, чем
        // минимальное и максимальное число аргументов, которое передается в метод
        if (!is_null($getterKey)) {
            $min--;
            $max--;
            $debug = ' (с учетом getterKey)';
        } else {
            $debug = '';
        }

        $N = count($params);

        if ($N < $min) {
            throw new SelfEx("Переданное количество параметров: '{$N}' меньше минимального: '{$min}'{$debug}, которое принимает метод: '{$className}:{$method}'", 3);
        } elseif ($N > $max) {
            throw new SelfEx("Переданное количество параметров: '{$N}' больше максимального: '{$max}'{$debug}, которое принимает метод: '{$className}:{$method}'", 4);
        }

        $this->queries[] = [
            'class'      => $class,
            'class_name' => $className,
            'method'     => $method,
            'params'     => $params,
            'setter_key' => $setterKey,
            'getter_key' => $getterKey
        ];
    }


    /**
     * Предназначен для старта транзакции (выполнения добавленных запросов)
     *
     * @return $this объект текущего класса для последующей цепочки вызовов
     * @throws DataBaseEx
     */
    public function start(): self
    {
        parent::executeTransaction($this);
        return $this;
    }


    /**
     * Предназначен для получения массива результатов последней транзакции
     *
     * @return array
     */
    public function getLastResults(): array
    {
        return $this->lastResults;
    }


    /**
     * Предназначен для выполнения всех добавленных запросов
     *
     * Вызывается в классе DataBase внутри обертки транзакции. Записывает результаты запросов в массив lastResults
     *
     * @throws SelfEx
     * @throws DataBaseEx
     */
    protected function executeQueries(): void
    {
        $this->lastResults = [];

        foreach ($this->queries as $query) {

            // Установка нужного предыдущего результата, как первого переданного аргумента в текущий метод
            if (isset($query['getter_key'])) {

                if (!isset($this->chainResults[$query['getter_key']])) {

                    throw new SelfEx("В метод: '{$query['class_name']}::{$query['method']}' не удалось передать результат по цепочке, т.к. значение chainResults['{$query['getter_key']}']  не инициализировано", 5);
                }

                $chainResult = [$this->chainResults[$query['getter_key']]];
            } else {

                $chainResult = [];
            }

            $lastResult = call_user_func_array([$query['class'], $query['method']], [...$chainResult, ...$query['params']]);

            $this->lastResults[$query['class_name']][$query['method']][] = $lastResult;

            if (!is_null($query['setter_key'])) $this->chainResults[$query['setter_key']] = $lastResult;
        }

        // Удаление данных текущей транзакции
        $this->queries = [];
        $this->chainResults = [];
    }
}