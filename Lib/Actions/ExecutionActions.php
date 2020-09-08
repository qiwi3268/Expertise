<?php


namespace Lib\Actions;
use Exception as SelfEx; //todo


class ExecutionActions
{

    private string $childClassName; // Имя вызывающего дочернего класса для отладки
    private Actions $actions;


    public function __construct(Actions $actions)
    {
        $this->childClassName = static::class;
        $this->actions->$actions;
    }


    public function checkIssetCallbackByPageName(string $pageName = URN): void
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        $method = $action['callback_name'];

        if (!method_exists($this, $method)) {
            throw new SelfEx("Отсутствует метод выполнения действия: '{$this->childClassName}:{$method}'", 111);
        }
    }


    // Возвращает параметер - страница, на которую нужно перенаправить после действия
    public function executeCallbackByHash(string $hash): string
    {
        $action = $this->actions->getAssocActionByHash($hash);

        if (is_null($action)) {
            throw new SelfEx("Не найден метод выполнения действия по требуемому hash'у: {$hash}'", 111);
        }

        $method = $action['callback_name'];

        return $this->$method();
    }
}