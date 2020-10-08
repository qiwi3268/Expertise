<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса обработки данных дерева документа {@see \Lib\Singles\DocumentTreeHandler}
 *
 * 1 - экземпляр объекта по ключу ... уже существует в хранилище<br>
 * 2 - экземпляр объекта по ключу ... не существует в хранилище
 *
 */
class DocumentTreeHandler extends \Exception
{
    use MainTrait;
}
