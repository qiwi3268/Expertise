<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе csp класса {@see \Lib\CSP\MessageParser}
 *
 * 1 - в БД не нашлось имени из ФИО<br>
 * 2 - в одном Signer нашлось больше одного ФИО<br>
 *
 */
class CSPMessageParser extends \Exception
{
    use MainTrait;
}