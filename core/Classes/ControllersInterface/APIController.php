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

            $this->complexExitWithException('Uncaught ErrorException', $e, 'Необработанное исключение, полученное путем преобразования ошибки');
        } catch (DataBaseEx $e) {

            $this->complexExitWithException('Uncaught DataBase Exception', $e, 'Необработанное исключение базы данных');
        } catch (LoggerEx $e) {

            $this->exitWithException('Uncaught Logger Exception', $e, 'Необработанное исключение логгера сообщений');
        } catch (Exception $e) {

            $this->complexExitWithException('Uncaught Exception', $e, 'Необработанное исключение');
        }

        $debug = static::class;
        $this->exit('No exit', "Класс: '{$debug}' не завершил свою работу выходом");
    }


    /**
     * Предназначен для комплексного завершения работы скрипта,
     * включая логирование сообщения и обработку возможного исключения логгера
     *
     * @uses \Lib\Singles\Logger::writeException()
     * @uses \core\Classes\ControllersInterface\APIController::exitWithException()
     * @param string $result уникальный код выхода
     * @param Exception $e исключение
     * @param string $description дополнительное описание
     */
    protected function complexExitWithException(string $result, Exception $e, string $description): void
    {
        try {
            $this->errorLogger->writeException($e, $description);
        } catch (LoggerEx $e_logger) {
            $description = exceptionToString($e_logger, "Произошла непредвиденная ошибка при логировании необработанного исключения с описанием: {$description}");
        }
        $this->exitWithException($result, $e, $description);
    }


    /**
     * Предназначен для завершения работы скрипта с результирующим json'ом
     *
     * @param string $result уникальный код выхода
     * @param string $message выходное сообщение
     */
    protected function exit(string $result, string $message): void
    {
        exit(json_encode([
            'result'  => $result,
            'message' => $message
        ]));
    }


    /**
     * Предназначен для завершения работы скрипта с масивом произвольного формата
     *
     * @param string $result уникальный код выхода
     * @param array $array ассоциативный массив, который будет распакован в выходной json.<br>
     * В данном массиве <b>не должно быть элемента по ключу result</b>
     *
     */
    protected function customExit(string $result, array $array): void
    {
        $array['result'] = $result;
        exit(json_encode($array));
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
    protected function exitWithException(string $result, Exception $e, string $description = ''): void
    {
        $this->exit($result, exceptionToString($e, $description));
    }


    /**
     * Предназначен для проверки наличия обязательных параметров http запроса
     *
     * В случае отсутствия параметров производится выход с соответствующим кодом ошибки
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

        $this->exit($res, $msg);
    }


    /**
     * Предназначен для получения ассоциативного массива обязательных параметров http запроса
     *
     * Метод включает в себя проверку наличия параметров
     *
     * @uses \core\Classes\ControllersInterface\APIController::checkRequiredParams()
     * @param string $method требуемый тип запроса на сервер
     * @param array $params массив необходимых параметров
     * @return array ассоциативный массив обязательных параметров
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