<?php


namespace Lib\Exceptions;


// exception, связанный с ошибками при работе csp класса MessageParser
// code:
//  1 - в БД не нашлось имени из ФИО
//  2 - в одном Signer нашлось больше одного ФИО
class CSPMessageParser extends \Exception
{
    use \Lib\Exceptions\Traits\MainTrait;
}