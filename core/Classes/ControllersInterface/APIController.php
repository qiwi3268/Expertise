<?php


namespace core\Classes\ControllersInterface;

use core\Classes\Exceptions\Request as RequestEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Logger as LoggerEx;
use ErrorException;
use Exception;

use core\Classes\Request\Request;
use core\Classes\Request\HttpRequest;
use Lib\ErrorTransform\ErrorTransformer;
use Lib\ErrorTransform\Handlers\ErrorExceptionHandler;
use Lib\Singles\Logger;


/**
 * Предоставляет индерфейс для классов API-контроллеров
 *
 * Класс устанавливает пользовательский преобразователь ошибок в исключения
 *
 */
abstract class APIController extends Controller
{

    /**
     * Объект запроса на сервер
     *
     */
    protected Request $request;

    /**
     * Объект логгера ошибок
     *
     */
    private Logger $errorLogger;

    /**
     * Необходим для того, чтобы преобразователь ошибок устанавливался единожды,
     * даже если будут вызываться несколько реализующих классов
     *
     */
    static private bool $errorTransformerWasSet = false;

    /**
     * Код выхода, соответствующий успешному завершению работы API
     *
     */
    protected const SUCCESS_RESULT = 'ok';

    /**
     * Код выхода, соответствующий отсутствию обязательных параметров POST запроса
     *
     */
    protected const MISSING_POST_PARAMS_RESULT = 'mppr';

    /**
     * Код выхода, соответствующий отсутствию обязательных параметров GET запроса
     *
     */
    protected const MISSING_GET_PARAMS_RESULT = 'mgpr';


    /**
     * Конструктор класса без входных параметров
     *
     */
    public function __construct()
    {
        $this->request = HttpRequest::getInstance();
        $this->errorLogger = $this->getErrorLogger();
    }


    /**
     * Реализация абстрактного метода
     *
     * <b>*</b> Предназначен для вызова из контекста RoutesXMLHandler
     *
     */
    public function execute(): void
    {
        // Установка происходит единожды
        if (!self::$errorTransformerWasSet) {
            new ErrorTransformer(new ErrorExceptionHandler(), false);
            self::$errorTransformerWasSet = true;
        }

        try {

            $this->doExecute();
        } catch (ErrorException $e) {

            $this->logAndExceptionExit('Uncaught ErrorException', $e, 'Необработанное исключение, полученное путем преобразования ошибки');
        } catch (DataBaseEx $e) {

            $this->logAndExceptionExit('Uncaught DataBase Exception', $e, 'Необработанное исключение базы данных');
        } catch (LoggerEx $e) {
            // Не имеет смысла логировать в случае выбрасывания исключения из самого логгера
            $this->exceptionExit('Uncaught Logger Exception', $e, 'Необработанное исключение логгера сообщений');
        } catch (Exception $e) {

            $this->logAndExceptionExit('Uncaught Exception', $e, 'Необработанное исключение');
        }

        $debug = static::class;
        $this->errorExit('No exit', "Класс: '{$debug}' не завершил свою работу выходом");
    }


    /**
     * Предназначен для логирования сообщения об ошибке и завершения работы скрипта
     * с преобразованием исключения в строку
     *
     * @uses \Lib\Singles\Logger::writeException()
     * @uses \core\Classes\ControllersInterface\APIController::exceptionExit()
     * @param string $result
     * @param Exception $e
     * @param string $description
     */
    protected function logAndExceptionExit(string $result, Exception $e, string $description = ''): void
    {
        try {
            $this->errorLogger->writeException($e, $description);
        } catch (LoggerEx $e_logger) {
            $description = exceptionToString($e_logger) . '. ' . $description;
        }
        $this->exceptionExit($result, $e, $description);
    }


    /**
     * Предназначен для завершения работы скрипта
     * с преобразованием исключения в строку
     *
     * @uses \core\Classes\ControllersInterface\APIController::exit()
     * @param string $result уникальный код выхода
     * @param Exception $e исключение
     * @param string $description дополнительное описание
     */
    protected function exceptionExit(string $result, Exception $e, string $description = ''): void
    {
        $this->exit([
            'result'  => $result,
            'message' => exceptionToString($e, $description)
        ]);
    }


    /**
     * Предназначен для логирования сообщения об ошибке и завершения работы скрипта при неудачном выполнении
     *
     * @uses \Lib\Singles\Logger::write()
     * @uses \core\Classes\ControllersInterface\APIController::errorExit()
     * @param string $result
     * @param string $message
     * @param array $additional
     */
    protected function logAndErrorExit(string $result, string $message, array $additional = []): void
    {
        try {
            $this->errorLogger->write($message);
        } catch (LoggerEx $e_logger) {
            $message = exceptionToString($e_logger) . '. ' . $message;
        }
        $this->errorExit($result, $message, $additional);
    }


    /**
     * Предназначен для завершения работы скрипта при неудачном выполнении
     *
     * @param string $result уникальный код выхода
     * @param string $message выходное сообщение
     * @param array $additional ассоциативный массив, который будет распакован в выходной json.<br>
     * В данном массиве <i>не должно</i> быть элемента по ключу <b>result</b> и <b>message</b>
     */
    protected function errorExit(string $result, string $message, array $additional = []): void
    {
        $additional['result'] = $result;
        $additional['message'] = $message;
        $this->exit($additional);
    }


    /**
     * Предназначен для завершения работы скрипта при успешном выполнении
     *
     * @param array $additional ассоциативный массив, который будет распакован в выходной json.<br>
     * В данном массиве <i>не должно</i> быть элемента по ключу <b>result</b>
     */
    protected function successExit(array $additional = []): void
    {
        $additional['result'] = self::SUCCESS_RESULT;
        $this->exit($additional);
    }


    /**
     * Предназначен для завершения работы скрипта с результирующим json'ом
     *
     * @param array $result массив результатов
     */
    private function exit(array $result): void
    {
        exit(json_encode($result));
    }


    /**
     * Предназначен для проверки наличия обязательных параметров http запроса
     *
     * В случае отсутствия параметров производится логирование ошибки и выход с соответствующим кодом
     *
     * @param string $method требуемый тип запроса на сервер
     * @param string[] $params массив необходимых параметров
     */
    protected function checkRequiredParams(string $method, array $params): void
    {
        if ($this->request->checkRequestMethod($method)) {

            $missing = array_diff($params, array_keys($this->request->getAll()));
            if (empty($missing)) return;
        } else {
            $missing = $params;
        }
        $msg = "Нет обязательных параметров {$method} запроса: " . implode(', ', $missing);
        $res = $method == HttpRequest::POST ? self::MISSING_POST_PARAMS_RESULT : self::MISSING_GET_PARAMS_RESULT;

        $this->logAndErrorExit($res, $msg);
    }


    /**
     * Предназначен для получения ассоциативного массива обязательных параметров http запроса
     *
     * Метод включает в себя проверку наличия параметров
     *
     * @uses \core\Classes\ControllersInterface\APIController::checkRequiredParams()
     * @param string $method требуемый тип запроса на сервер
     * @param array $params индексный массив необходимых параметров
     * @return array ассоциативный массив необходимых параметров
     * @throws RequestEx
     */
    protected function getCheckedRequiredParams(string $method, array $params): array
    {
        $this->checkRequiredParams($method, $params);
        $result = [];
        foreach ($params as $param) {
            $result[$param] = $this->request->get($param);
        }
        return $result;
    }


    /**
     * Предназначен для получения логгера ошибок
     *
     * @return Logger
     */
    abstract protected function getErrorLogger(): Logger;
}