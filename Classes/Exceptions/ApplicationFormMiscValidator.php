<?php


namespace Classes\Exceptions;

use Lib\Exceptions\MiscValidator;
use Lib\Exceptions\MiscValidator as MiscValidatorEx;
use Exception;
use Throwable;



/**
 * Связан с ошибками при работе классов обработки справочников заявления
 *
 * <b>***</b> code соответствуют API_save_form result<br>
 *
 * {@see \Classes\Application\Miscs\Validation\SingleMisc}<br>
 * 4 - передано некорректное значение справочника<br>
 * 5 - запрашиваемое значение справочника не существует<br>
 * {@see \Classes\Application\Miscs\Validation\DependentMisc}<br>
 * 4 - передано некорректное значение справочника<br>
 * 5 - запрашиваемое значение справочника не существует<br>
 * 7 - при наличии значения зависимого справочника, флаг наличия проверенных данных главного справочника отрицательный
 *
 */
class ApplicationFormMiscValidator extends Exception
{

    /**
     * Конструктор класса
     *
     * Выполняет преобразования исключения {@see \Lib\Exceptions\MiscValidator}
     * в исключение текущего типа с result API_save_form
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @throws MiscValidatorEx в случае, если преобразование к текущему типу исключения не определено
     */
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        $newCode = $code;

        if (
            !is_null($previous)
            && $previous instanceof MiscValidatorEx
        ) {

            switch ($code) {
                case 1 :
                    $newCode = 4;
                    break;
                case 4 :
                    $newCode = 5;
                    break;
                case 5 :
                    $newCode = 7;
                    break;
                default :
                    throw new MiscValidatorEx($message, $code);
            }
        }

        parent::__construct($message, $newCode, null);
    }
}