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
     * @param string $class название класса по работе с таблицами
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
        string $class,
        string $method,
        array $params,
        ?string $setterKey = null,
        ?string $getterKey = null
    ): void {

        if (!class_exists($class)) {
            throw new SelfEx("Переданный класс: '{$class}' не существует", 1);
        }

        if (!method_exists($class, $method)) {
            throw new SelfEx("Переданный метод: '{$class}:{$method}' не существует", 2);
        }

        $reflection = new ReflectionMethod($class, $method);
        $min = $reflection->getNumberOfRequiredParameters();
        if (!is_null($getterKey)) $min--;

        $max = $reflection->getNumberOfParameters();

        $N = count($params);

        if ($N < $min) {
            throw new SelfEx("Переданное количество параметров: '{$N}' меньше минимального: '{$min}', которое принимает метод: '{$class}:{$method}'", 3);
        } elseif ($N > $max) {
            throw new SelfEx("Переданное количество параметров: '{$N}' больше максимального: '{$max}', которое принимает метод: '{$class}:{$method}'", 4);
        }

        $this->queries[] = [
            'class'      => $class,
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
     */
    protected function executeQueries(): void
    {
        $this->lastResults = [];

        foreach ($this->queries as $query) {

            // Установка нужного предыдущего результата, как первого переданного аргумента в текущий метод
            if (isset($query['getter_key'])) {

                if (!isset($this->chainResults[$query['getter_key']])) {

                    throw new SelfEx("В метод: '{$query['class']}::{$query['method']}' не удалось передать результат по цепочке, т.к. в значение  chainResults['{$query['getter_key']}']  не инициализировано", 5);
                }

                $chainResult = [$this->chainResults[$query['getter_key']]];
            } else {

                $chainResult = [];
            }

            $lastResult = call_user_func_array([$query['class'], $query['method']], [...$chainResult, ...$query['params']]);

            $this->lastResults[$query['class']][$query['method']][] = $lastResult;

            if (!is_null($query['setter_key'])) $this->chainResults[$query['setter_key']] = $lastResult;
        }
    }
}