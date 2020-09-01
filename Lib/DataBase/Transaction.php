<?php


namespace Lib\DataBase;

use Lib\Exceptions\Transaction as SelfEx;


// Предназначен для выполнения транзакций
//
final class Transaction extends DataBase
{

    // Массив добавленных запросов
    private array $queries = [];

    // Массив результатов последней транзации
    public array $lastResults = [];


    // Предназначен для добавления запроса в список транзакций
    // Принимает параметры-----------------------------------
    // class  string : название класса по работе с таблицами
    // method string : название метода класса
    // params  array : массив параметров, каждый из которых будет передан как аргумент метода method
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Transaction:
    // code:
    //  1 - переданный класс не существует
    //  2 - переданный метод не существует
    //  3 - Переданное количество параметров меньше минимального, которое принимает метод
    //  4 - Переданное количество параметров больше максимального, которое принимает метод
    //
    public function add(string $class, string $method, array $params): void
    {
        if (!class_exists($class)) {
            throw new SelfEx("Переданный класс: '{$class}' не существует", 1);
        }

        if (!method_exists($class, $method)) {
            throw new SelfEx("Переданный метод: '{$class}:{$method}' не существует", 2);
        }

        $reflection = new \ReflectionMethod($class, $method);
        $min = $reflection->getNumberOfRequiredParameters();
        $max = $reflection->getNumberOfParameters();

        $N = count($params);

        if ($N < $min) {
            throw new SelfEx("Переданное количество параметров: '{$N}' меньше минимального: '{$min}', которое принимает метод: '{$class}:{$method}'", 3);
        } elseif ($N > $max) {
            throw new SelfEx("Переданное количество параметров: '{$N}' больше максимального: '{$max}', которое принимает метод: '{$class}:{$method}'", 4);
        }

        $this->queries[] = [
            'class' => $class,
            'method' => $method,
            'params' => $params
        ];
    }


    // Предназначен для старта транзакции (выполнения добавленных запросов)
    // Возвращает параметры-----------------------------------
    // self : объект текущего класса для последующей цепочки вызовов (lastResults)
    //
    public function start(): self
    {
        parent::executeTransaction($this);
        return $this;
    }


    // Предназначен для выполнения всех добавленных запросов (вызывается в классе DataBase внутри обертки транзакции)
    // Записывает результаты запросов в массив lastResults
    //
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