<?php


namespace Lib\Exceptions\Traits;


/**
 * Главный trait для переопределения констркутора класса {@see \Exception}
 *
 */
trait MainTrait
{

    /**
     * Конструктор класса
     *
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}