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
 */
abstract class APIController extends Controller
{

    private Logger $logger;

    /**
     * Ссылка на преобразователь ошибок в исключения
     *
     * Таким образом предыдущий обработчик ошибок будет восстановлен
     * только после уничтожения всех ссылок на текущий объект
     *
     */
    static private ErrorTransformer $errorTransformer;


    /**
     * Конструктор класса без входных параметров
     *
     */
    final public function __construct()
    {
        parent::__construct();
        $this->logger = $this->getLogger();
    }


    /**
     * Реализация абстрактного метода
     *
     * <b>*</b> Должен вызываться единожды и только из контекста RoutesXMLHandler
     *
     */
    public function execute(): void
    {
        if (!isset(self::$errorTransformer)) {
            self::$errorTransformer = new ErrorTransformer(new ErrorExceptionHandler(), true);
        }

        try {

            $this->doExecute();
        } catch (ErrorException $e) {

            $this->complexExit('Uncaught ErrorException', $e, 'Необработанное исключение, полученное путем преобразования ошибки');
        } catch (DataBaseEx $e) {

            $this->complexExit('Uncaught DataBase Exception', $e, 'Необработанное исключение базы данных');
        } catch (Exception $e) {

            $this->complexExit('Uncaught Exception', $e, 'Необработанное исключение');
        }

        //todo проверить что тут мы не должны оказаться
    }


    private function complexExit(string $result, Exception $e, string $description): void
    {
        try {
            $this->logger->writeException($e, $description);
        } catch (LoggerEx $e) {
            $result
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
     * Вспомогательный метод. Предназначен для завершения
     * работы скрипта с преобразованием исключения в строку
     *
     * @uses \core\Classes\ControllersInterface\APIController
     * @param string $result уникальный код выхода
     * @param Exception $e исключение
     * @param string $description дополнительное описание
     */
    protected function exitWithException(string $result, Exception $e, string $description = ''): void
    {
        $this->exit($result, exceptionToString($e, $description));
    }


    /**
     * Предназначен для получения логгера
     *
     * @return Logger
     */
    abstract protected function getLogger(): Logger;
}