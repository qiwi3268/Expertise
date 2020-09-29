<?php


namespace Lib\DefaultFormParameters;

use Lib\Exceptions\DefaultFormParameters as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\DataBase\Transaction;


/**
 * Предназначен для получения параметров по умолчанию для html-форм
 *
 */
abstract class DefaultFormParametersCreator
{

    /**
     * Результаты выполнения транзакции
     *
     */
    private array $transactionResults;

    /**
     * Маппинг названия блока данных и результатов транзакции - ассоциативный массив формата:
     *
     * Ключ - уникальное название для полученного блока данных<br>
     * Значение - ассоциативный массив формата:<br>
     * ['class' => имя класса, 'method' => имя метода]<br>
     * То есть это ключи, по которым можно забрать нужный результат из транзакции
     *
     */
    private array $dataMapping;


    /**
     * Конструктор класса
     *
     * @param Transaction $transaction транзакция, при выполнении которой будут получены данные
     * для получения параметров по умолчанию
     * @param array $dataMapping маппинг названия блока данных и результатов транзакции
     * @throws DataBaseEx
     * @throws SelfEx
     */
    public function __construct(Transaction $transaction, array $dataMapping)
    {
        $transactionResults = $transaction->start()->getLastResults();

        // Проверка корректности маппинга и результатов транзакции
        foreach ($dataMapping as $key => $value) {

            if (
                !isset($value['class'])
                || !isset($value['method'])
            ) {
                throw new SelfEx("В dataMapping по ключу: '{$key}' отсутствует ключ 'class' или 'method'", 1);
            }

            list('class' => $class, 'method' => $method) = $value;

            if (!isset($transactionResults[$class][$method])) {
                throw new SelfEx("В результатах транзакции отсутствуют данные из вызова метода: {$class}::{$method}", 2);
            }
        }

        $this->transactionResults = $transactionResults;
        $this->dataMapping = $dataMapping;
    }


    /**
     * Предназначен для получения результатов транзакции по ключу из dataMapping
     *
     * @param string $key название блока данных
     * @return array результат транзакции
     * @throws SelfEx
     */
    protected function getResult(string $key): array
    {
        if (!isset($this->dataMapping[$key])) {
            throw new SelfEx("В dataMapping нет элемента по запрашиваемому ключу: '{$key}'", 3);
        }

        list('class' => $class, 'method' => $method) = $this->dataMapping[$key];

        return $this->transactionResults[$class][$method];
    }


    /**
     * Предназначен для получения параметров по умолчанию
     *
     * @return array
     */
    abstract public function getDefaultParameters(): array;
}