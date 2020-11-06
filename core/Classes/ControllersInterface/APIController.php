<?php


namespace core\Classes\ControllersInterface;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Logger as LoggerEx;
use ErrorException;
use Exception;

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
     * Логгер ошибок
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
     * Конструктор класса без входных параметров
     *
     */
    public function __construct()
    {
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
        $this->exit('No exit', "Класс : '{$debug}' не завершил свою работу выходом");
    }


    /**
     * Вспомогательный метод. Предназначен для комплексного завершения работы скрипта,
     * включая логирование сообщения и обработку возможного исключения логгера
     *
     * @uses \Lib\Singles\Logger::writeException()
     * @uses \core\Classes\ControllersInterface\APIController::exitWithException()
     * @param string $result уникальный код выхода
     * @param Exception $e исключение
     * @param string $description дополнительное описание
     */
    private function complexExitWithException(string $result, Exception $e, string $description): void
    {
        try {
            $this->errorLogger->writeException($e, $description);
        } catch (LoggerEx $e) {
            $description = exceptionToString($e, 'Произошла непредвиденная ошибка при логировании необработанного исключения') . '. ' . $description;
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
        vd([
            'result'  => $result,
            'message' => $message
        ]);
        exit(json_encode([
            'result'  => $result,
            'message' => $message
        ]));
    }


    /**
     * Предназначен для нестандартного завершения работы скрипта
     *
     * @param string $result уникальный код выхода
     * @param array $array ассоциативный массив, который будет распакован в выходной json.<br>
     * В данном массиве <b>не должно быть элемента по ключу result</b>
     *
     */
    protected function customExit(string $result, array $array): void
    {
        $arr['result'] = $result;

        foreach ($array as $key => $value) {
            $arr[$key] = $value;
        }
        exit(json_encode($arr));
    }



    /**
     * Вспомогательный метод. Предназначен для завершения
     * работы скрипта с преобразованием исключения в строку
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
     * Предназначен для получения логгера ошибок
     *
     * @return Logger
     */
    abstract protected function getErrorLogger(): Logger;
}