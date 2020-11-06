<?php


namespace core\Classes\Request;

use core\Classes\Exceptions\Request as SelfEx;


/**
 * Обеспечивает работу с http-запросом на сервер
 *
 */
class HttpRequest extends Request
{
    /**
     * Тип запроса на сервер
     *
     */
    private string $requestMethod;

    public const GET = 'GET';
    public const POST = 'POST';

    /**
     * Параметры запроса
     *
     * Не очищенные и не экранированные от html-тегов и специальных символов
     *
     */
    private array $dirtyProperties;


    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    protected function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case self::GET :
                // Удаление первого get-параметра (части запроса)
                unset($_GET[array_key_first($_GET)]);
                $properties = $_GET;
                break;
            case self::POST :
                $properties = $_POST;
                break;
            default :
                throw new SelfEx("Получен неопределенный метод запроса на сервер: '{$method}'", 2001);
        }

        $this->requestMethod = $method;
        $this->dirtyProperties = $properties;

        array_walk_recursive($properties, function(&$value): void
        {
            $value = htmlspecialchars(strip_tags($value), ENT_NOQUOTES);
        });

        $this->properties = $properties;
    }


    /**
     * Предназначен для проверки типа метода запроса
     *
     * @param string $method метод запроса (GET/POST)
     * @return bool
     */
    public function checkRequestMethod(string $method): bool
    {
        return $this->requestMethod == $method;
    }


    /**
     * Предназначен для получения неочищенного параметра запроса
     *
     * @param string $key наименование параметра
     * @return mixed
     * @throws SelfEx
     */
    public function getDirty(string $key)
    {
        return $this->checkIsset($key)->dirtyProperties[$key];
    }
}