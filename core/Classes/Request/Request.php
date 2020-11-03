<?php


namespace core\Classes\Request;

use core\Classes\Exceptions\Request as SelfEx;


/**
 * todo
 *
 */
abstract class Request
{

    /**
     * Сущность класса
     *
     */
    static private self $instance;

    /**
     * Параметры запроса
     *
     */
    protected array $properties;

    /**
     * Очищенные параметры запроса
     *
     */
    protected array $clearProperties;


    /**
     * Предназначен для получения сущности класса
     *
     * @return static сущность класса
     */
    static public function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }


    /**
     * Конструктор класса
     *
     */
    abstract protected function __construct();
}