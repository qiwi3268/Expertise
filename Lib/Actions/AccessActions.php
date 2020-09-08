<?php


namespace Lib\Actions;
use Exception as SelfEx; //todo


class AccessActions
{

    private string $childClassName; // Имя вызывающего дочернего класса для отладки
    private Actions $actions;


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
    //
    public function checkAccessFromActionByPageName(string $pageName = URN): bool
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        if (is_null($action)) {
            throw new SelfEx("Попытка получить доступ к несуществующему действию для страницы:'{$pageName}'", 3);
        }

        return $this->getValidatedCallbackResult($action['callback_name']);
    }


    // Предназначен для получения проверенного результата callback'а
    // Принимает параметры-----------------------------------
    // method string : название метода, который должен присутствовать в дочернем классе
    // Возвращает параметры----------------------------------
    // bool : результат callback'а
    //
    public function getValidatedCallbackResult(string $method): bool
    {
        if (!method_exists($this, $method)) {
            throw new SelfEx("Отсутствует метод доступа к действию: '{$this->childClassName}::{$method}'", 1);
        }

        if (!is_bool($result = $this->$method())) {
            throw new SelfEx("Метод доступа действию: '{$this->childClassName}::{$method}' возвращает значение, не принадлежащее типу boolean", 2);
        }

        return $result;
    }
}