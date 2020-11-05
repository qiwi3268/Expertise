<?php


namespace ControllersClasses;

use core\Classes\Request\HttpRequest;
use ErrorException;


class Test extends Controller
{

    public function doExecute(): void
    {

        set_error_handler([$this, 'handler']);

        //str_replace();

        $a
    }


    /**
     * Пользовательский преобразователь ошибок в исключение типа ErrorException
     *
     * @param int $severity уровень ошибки в виде целого числа
     * @param string $message сообщение об ошибке
     * @param string $file имя файла, в котором произошла ошибка
     * @param string $line номер строки, в которой произошла ошибка, в виде целого числа
     * @return bool
     * @throws ErrorException
     */
    public function handler(int $severity, string $message, string $file, string $line): bool
    {
        // Вывод ошибок отключен или использован оператор @
        if (!(error_reporting() & $severity)) {
            return false;
        }

        $message = "Severity: {$this->friendlySeverity($severity)}. {$message}";

        throw new ErrorException($message, 0, $severity, $file, $line);
    }


    /**
     * Предназначен для преобразования уровня ошибки в человекопонятную строку
     *
     * @param $severity int уровень ошибки в виде целого числа
     * @return string
     */
    public function friendlySeverity(int $severity): string
    {
        $names = [];

        $consts = array_flip
        (
            array_slice
            (
                get_defined_constants(true)['Core'],
                0,
                15,
                true
            )
        );

        foreach ($consts as $code => $name) {
            if ($severity & $code) $names[] = $name;
        }
        return join(' | ', $names);
    }
}