<?php


namespace core\Classes\Request;

use core\Classes\Exceptions\Request as SelfEx;


/**
 * Обеспечивает работу с http запросом на сервер
 *
 * Объявляет константы URI и URN
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

        // Полный запрос с первым '/' и get-параметрами
        define('URI', $_SERVER['REQUEST_URI']);

        // Запрос в формате без первого '/' и get-параметров
        define('URN', mb_substr(parse_url(URI, PHP_URL_PATH), 1));
    }


    /**
     * Предназначен для проверки типа запроса
     *
     * @param string $method метод запроса (GET/POST)
     * @return bool
     */
    public function checkRequestMethod(string $method): bool
    {
        return $this->requestMethod == $method;
    }


    /**
     * Предназначен для проверки существования параметров запроса
     * с проверкой типа запроса на GET
     *
     * @param string ...$keys <i>перечисление</i> наименований параметров
     * @return bool
     */
    public function hasInGET(string ...$keys): bool
    {
        return $this->requestMethod == self::GET && call_user_func_array([$this, 'has'], $keys);
    }


    /**
     * Предназначен для проверки существования параметров запроса
     * с проверкой типа запроса на POST
     *
     * @param string ...$keys <i>перечисление</i> наименований параметров
     * @return bool
     */
    public function hasInPOST(string ...$keys): bool
    {
        return $this->requestMethod == self::POST && call_user_func_array([$this, 'has'], $keys);
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


    /**
     * Предназначен для получения всех неочищенных параметров запроса
     *
     * @return array
     */
    public function getAllDirty(): array
    {
        return $this->dirtyProperties;
    }
}