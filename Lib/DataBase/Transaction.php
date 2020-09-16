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
     * Результат, передающися по цепочке вызовов
     *
     * @var mixed
     */
    private $chainResult = 'not_initialized';

    /**
     * Массив результатов последней транзации
     *
     */
    public array $lastResults = [];


    /**
     * Предназначен для добавления запроса в список транзакций
     *
     * @param string $class название класса по работе с таблицами
     * @param string $method название метода класса
     * @param array $params массив параметров, каждый из которых будет передан как аргумент метода method
     * @param bool $setChainResult <b>true</b> результат вызова текущего метода будет записан в значение, передающееся по цепочке<br>
     * <b>false</b> не будет записан
     * @param bool $getChainResult <b>true</b> результат вызова предыдущего метода, в котором было установлено
     * <b>setChainResult = true</b> будет передан как первый аргумент в текущий метод<br>
     * <b>false</b> не будет передан
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function add(
        string $class,
        string $method,
        array $params,
        bool $setChainResult = false,
        bool $getChainResult = false
    ): void {

        if (!class_exists($class)) {
            throw new SelfEx("Переданный класс: '{$class}' не существует", 1);
        }

        if (!method_exists($class, $method)) {
            throw new SelfEx("Переданный метод: '{$class}:{$method}' не существует", 2);
        }

        $reflection = new ReflectionMethod($class, $method);
        $min = $reflection->getNumberOfRequiredParameters();
        if ($getChainResult) $min--;

        $max = $reflection->getNumberOfParameters();

        $N = count($params);

        if ($N < $min) {
            throw new SelfEx("Переданное количество параметров: '{$N}' меньше минимального: '{$min}', которое принимает метод: '{$class}:{$method}'", 3);
        } elseif ($N > $max) {
            throw new SelfEx("Переданное количество параметров: '{$N}' больше максимального: '{$max}', которое принимает метод: '{$class}:{$method}'", 4);
        }

        $this->queries[] = [
            'class'            => $class,
            'method'           => $method,
            'params'           => $params,
            'set_chain_result' => $setChainResult,
            'get_chain_result' => $getChainResult
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

            // Установка последнего результата, как первого переданного аргумента в текущий метод
            if ($query['get_chain_result']) {

                if ($this->chainResult === 'not_initialized') {
                    throw new SelfEx("В метод: '{$query['class']}::{$query['method']}' не удалось передать результат по цепочке, т.к. chainResult не инициализирован", 5);
                }
                $chainResult = [$this->chainResult];
            } else {

                $chainResult = [];
            }

            $lastResult = call_user_func_array([$query['class'], $query['method']], [...$chainResult, ...$query['params']]);

            $this->lastResults[] = $lastResult;

            if ($query['set_chain_result']) $this->chainResult = $lastResult;
        }
    }
}