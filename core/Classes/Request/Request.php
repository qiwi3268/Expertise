<?php


namespace core\Classes\Request;

use core\Classes\Exceptions\Request as SelfEx;


/**
 * Предоставляет интерфейс и базовый функционал для классов обработки запросов на сервер
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
     * Представляет собой ассоциативный массив
     *
     */
    protected array $properties;


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
     * Предназначен для проверки существования параметров запроса
     *
     * @param string ...$key <i>перечисление</i> наименований параметров
     * @return bool
     */
    public function has(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (!isset($this->properties[$key])) {
                return false;
            }
        }
        return true;
    }


    /**
     * Предназначен для быстрого получения параметров запроса
     *
     * @uses \core\Classes\Request\Request::get()
     * @param string $key
     * @return mixed
     * @throws SelfEx
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }


    /**
     * Предназначен для получения параметра запроса
     *
     * @param string $key наименование параметра
     * @return mixed
     * @throws SelfEx
     */
    public function get(string $key)
    {
        return $this->checkIsset($key)->properties[$key];
    }


    /**
     * Предназначен для получения всех параметров запроса
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->properties;
    }


    /**
     * Предназначен для внутренней проверки существования параметра запроса
     * и выбрасывания исключения в случае, если параметр отсутствует
     *
     * @param string $key
     * @return $this
     * @throws SelfEx
     */
    protected function checkIsset(string $key): self
    {
        if (!isset($this->properties[$key])) {
            throw new SelfEx("Запрашиваемый параметр по ключу: '{$key}' не существует", 1001);
        }
        return $this;
    }


    /**
     * Конструктор класса
     *
     * Дочерние классы должны заполнить массив параметров запроса
     *
     */
    abstract protected function __construct();
}