<?php

namespace Lib\ErrorTransform\Handlers;

use Lib\ErrorTransform\ErrorHandler;
use ErrorException;


/**
 * Предназначен для преобразования ошибок в исключение типа {@see ErrorException}
 *
 */
class ErrorExceptionHandler extends ErrorHandler
{

    /**
     * Реализация абстрактного метода
     *
     * @param int $severity
     * @param string $message
     * @param string $file
     * @param string $line
     * @return bool
     * @throws ErrorException
     */
    public function handler(int $severity, string $message, string $file, string $line): bool
    {
        // Вывод ошибок отключен или использован оператор @
        if (!(error_reporting() & $severity)) {
            return false;
        }
        $message = "Severity: {$this->getHumanSeverity($severity)}. {$message}";

        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}