<?php


namespace Lib\ErrorTransform;


/**
 * Предоставляет интерфейс для пользовательского обработчика ошибок
 *
 */
abstract class ErrorHandler
{

    /**
     * Предназначен для преобразования уровня ошибки в человекопонятную строку
     *
     * @param $severity int уровень ошибки в виде целого числа
     * @return string
     */
    protected function getHumanSeverity(int $severity): string
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

    /**
     * Пользовательский обработчик ошибок
     *
     * <b>*</b> Данный метод обязательно должен быть с областью видимости public для
     * использования внутри функции {@see set_error_handler()}
     *
     * @param int $severity уровень ошибки в виде целого числа
     * @param string $message сообщение об ошибке
     * @param string $file имя файла, в котором произошла ошибка
     * @param string $line номер строки, в которой произошла ошибка, в виде целого числа
     * @return bool
     */
    abstract public function handler(int $severity, string $message, string $file, string $line): bool;
}