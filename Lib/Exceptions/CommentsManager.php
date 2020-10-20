<?php


namespace Lib\Exceptions;

use Lib\Exceptions\Traits\MainTrait;


/**
 * Связан с ошибками при работе класса базы данных {@see \Lib\CommentsManager\CommentsManager}
 *
 * 1 - произошла ошибка при валидации массива с замечаниями<br>
 * 2 - справочник критичности замечания является обязательным к заполнению<br>
 * 3 - не должно быть отмеченного файла при выбранной опции: 'Отметка файлов не требуется'<br>
 * 4 - должен быть отмеченный файл, если не выбрана опция: 'Отметка файлов не требуется'<br>
 * 5 - отсутствует ссылка на нормативный документ<br>
 * 6 - присутствуют повторяющиеся hash'и замечаний<br>
 * 7 - запись файла с id: ... не существует в таблице класса: ...
 * 8 - запись замечания, находящаяся во входном json'е с id: ..., не существует в БД<br>
 * 9 - во входном json'e присутствуют замечания с id: ..., которых нет в БД
 *
 */
class CommentsManager extends \Exception
{
    use MainTrait;
}
