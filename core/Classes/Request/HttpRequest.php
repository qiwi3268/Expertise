<?php


namespace core\Classes\Request;

use core\Classes\Exceptions\Request as SelfEx;


/**
 * todo
 *
 */
class HttpRequest extends Request
{
    /**
     * Тиа запроса на сервер
     *
     */
    private string $requestMethod;

    public const GET = 'GET';
    public const POST = 'POST';


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
                unset($_GET[array_key_first($_GET)]);
                $properties = $_GET;
                break;
            case self::POST :
                $properties = $_POST;
                break;
            default :
                throw new SelfEx("Получен неопределенный метод запроса на сервер: '{$method}'", 2001);
        }



        $this->properties = $properties;
        $this->requestMethod = $method;
    }
}