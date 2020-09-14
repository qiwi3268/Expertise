<?php


namespace Lib\DataBase;

use Lib\Exceptions\Transaction as SelfEx;
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
     * @throws SelfEx
     */
    public function add(string $class, string $method, array $params): void
    {
        if (!class_exists($class)) {
            throw new SelfEx("Переданный класс: '{$class}' не существует", 1);
        }

        if (!method_exists($class, $method)) {
            throw new SelfEx("Переданный метод: '{$class}:{$method}' не существует", 2);
        }

        $reflection = new ReflectionMethod($class, $method);
        $min = $reflection->getNumberOfRequiredParameters();
        $max = $reflection->getNumberOfParameters();

        $N = count($params);

        if ($N < $min) {
            throw new SelfEx("Переданное количество параметров: '{$N}' меньше минимального: '{$min}', которое принимает метод: '{$class}:{$method}'", 3);
        } elseif ($N > $max) {
            throw new SelfEx("Переданное количество параметров: '{$N}' больше максимального: '{$max}', которое принимает метод: '{$class}:{$method}'", 4);
        }

        $this->queries[] = [
            'class'  => $class,
            'method' => $method,
            'params' => $params
        ];
    }


    /**
     * Предназначен для старта транзакции (выполнения добавленных запросов)
     *
     * @return $this объект текущего класса для последующей цепочки вызовов
     * @throws \Lib\Exceptions\DataBase
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
     */
    protected function executeQueries(): void
    {
        $this->lastResults = [];

        $results = [];

        foreach ($this->queries as $query) {

            $results[] = call_user_func_array([$query['class'], $query['method']], [...$query['params']]);
        }

        $this->lastResults = $results;
    }
}