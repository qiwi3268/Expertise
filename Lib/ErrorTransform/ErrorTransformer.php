<?php


namespace Lib\ErrorTransform;


/**
 * Предназначен для преобразования ошибок
 *
 */
class ErrorTransformer
{

    /**
     * Предыдущий обработчкик ошибок
     *
     */
    private $previousHandler;

    private bool $needRestore;


    /**
     * Конструктор класса
     *
     * Предназначен для установления обработчика ошибок
     *
     * @param ErrorHandler $handler пользовательский обработчик ошибок
     * @param bool $needRestore требуется ли восстанавливать предыдущий обработчик ошибок в дестркуторе класса
     */
    public function __construct(ErrorHandler $handler, bool $needRestore)
    {
        $this->previousHandler = set_error_handler([$handler, 'handler']);
        $this->needRestore = $needRestore;
    }


    /**
     * Дестркутор класса
     *
     * Предназначен для восстановления предыдущего обработчика ошибок
     *
     */
    public function __destruct()
    {
        if ($this->needRestore) restore_error_handler();
    }
}