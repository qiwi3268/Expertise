<?php


// exception, связанный с ошибками при работе класса проверки примитивов PrimitiveValidator
// code:
//  1 - ошибка при декодировании json-строки
//  2 - декодированная json-строка не является массивом
//  3 - в массиве, полученном из json-строки, присутствует нечисловой элемент
//  4 - в массиве, полученном из json-строки элемент найден более одного раза
//  5 - строковая дата является некорректной
//  6 - дата не существует по григорианскому календарю
//  7 - введенный ИНН является некорректным
//  8 - введенный КПП является некорректным
//  9 - введенный ОГРН является некорректным
// 10 - введенный email является некорректным
// 11 - введенный процент является некорректным
// 12 - введеное значение не является целочисленным
<<<<<<< HEAD
=======
// 13 - во входном массиве отсутствует обязательное поле
// 14 - значение входного массива по ключу не прошло проверку
// 15 - значение не подходит ни под одно из перечисленных
>>>>>>> 346c3228d8d85e51138fbddaff8753f22b7e3ce0
class PrimitiveValidatorException extends Exception{
    
    use Trait_exception;
}