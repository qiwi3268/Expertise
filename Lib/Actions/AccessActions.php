<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;


// Предназначен для проверки доступа пользователя к действиям
//
abstract class AccessActions
{

    private string $childClassName; // Имя вызывающего дочернего класса для отладки
    private Actions $actions;


    // Принимает параметры-----------------------------------
    // actions Actions : объект дочернего класса документа, унаследованного от библиотчечного класса Lib\Actions\Actions
    //
    public function __construct(Actions $actions)
    {
        $this->childClassName = static::class;
        $this->actions = $actions;
    }


    // Предназначен для получения ассоциативных массивов доступных действий
    // То есть тех действий, для которых callback вернул true
    // Возвращает параметры----------------------------------
    // array : ассоциативные массивы доступных действий
    //
    public function getAvailableActions(): array
    {
        $result = [];

        foreach ($this->actions->getAssocActiveActions() as $action) {

            if ($this->getValidatedCallbackResult($action['callback_name'])) {
                $result[] = $action;
            }
        }
        return $result;
    }


    // Предназначен для проверки доступа к действию по названию страницы
    // Принимает параметры-----------------------------------
    // pageName string : URN требуемой страницы
    // Возвращает параметры----------------------------------
    // true  : есть доступ к действию
    // false : нет доступа к действию
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  2 - попытка получить доступ к несуществующему действию для страницы
    //
    public function checkAccessFromActionByPageName(string $pageName = URN): bool
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        if (is_null($action)) {
            throw new SelfEx("Попытка получить доступ к несуществующему действию для страницы: '{$pageName}'", 2);
        }

        return $this->getValidatedCallbackResult($action['callback_name']);
    }


    // Предназначен для получения проверенного результата callback'а
    // Принимает параметры-----------------------------------
    // method string : название метода, который должен присутствовать в дочернем классе
    // Возвращает параметры----------------------------------
    // bool : результат callback'а
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  3 - отсутствует метод доступа к действию
    //  4 - метод доступа действию возвращает значение, не принадлежащее типу boolean
    //
    public function getValidatedCallbackResult(string $method): bool
    {
        if (!method_exists($this, $method)) {
            throw new SelfEx("Отсутствует метод доступа к действию: '{$this->childClassName}::{$method}'", 3);
        }

        if (!is_bool($result = $this->$method())) {
            throw new SelfEx("Метод доступа действию: '{$this->childClassName}::{$method}' возвращает значение, не принадлежащее типу boolean", 4);
        }

        return $result;
    }
}