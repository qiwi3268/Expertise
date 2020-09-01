<?php


namespace Classes\Exceptions;


// exception, связанный с ошибками при работе навигационного класса Navigation
// code:
//  1  - ошибка при инициализации XML-схемы навигации
//  2  - пользователю c ролями не определен ни один навигационный блок
//  3  - в XML-схеме навигации отсутствуют блоки
//  4  - в узле <block /> присутствуют дочерние элементы помимо <view /> и <ref />
//  5  - в XML-схеме навигации присутствуют узлы помимо <block>
//  6  - в узле <block /> присутствуют узлы <view /> с одинаковыми атрибутами name
//  7  - присутствуют узлы <block /> с одинаковыми аттрибутами name
//  8  - в узле среди аттрибутов ... не найден обязательный аттрибут
//  9  - в узле имеются аттрибуты помимо ...
//  10 - абстрактный класс навигационной страницы не существует
//  11 - требуемый класс не существует
//  12 - файл view по пути не существует
//  13 - аттрибут show_counter не равен 0 или 1
//  14 - внутренняя ссылка на внутренний ресурс должна начинаться с символа '/'
//
class Navigation extends \Exception{

    use \Lib\Exceptions\Traits\MainTrait;
}