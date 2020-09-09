<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;


// Предназначен для исполнения действия
//
abstract class ExecutionActions
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


    // Предназначен для проверки существования метода выполения действия по названию странцы
    // Принимает параметры-----------------------------------
    // pageName string : URN страницы
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  5 - отсутствует метод выполнения действия
    //
    public function checkIssetCallbackByPageName(string $pageName = URN): void
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        $method = $action['callback_name'];

        if (!method_exists($this, $method)) {
            throw new SelfEx("Отсутствует метод выполнения действия: '{$this->childClassName}:{$method}'", 5);
        }
    }


    // Предназначен для выполнения действия по его названию страницы
    // Принимает параметры-----------------------------------
    // pageName string : URN страницы
    // Возвращает параметры----------------------------------
    // string : URN страницы, на которую необходимо перенаправить пользователя после действия
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  6 - не найден метод выполнения действия по требуемому hash'у
    //
    public function executeCallbackByHash(string $hash): string
    {
        $action = $this->actions->getAssocActionByHash($hash);

        if (is_null($action)) {
            throw new SelfEx("Не найден метод выполнения действия по требуемому hash'у: {$hash}'", 6);
        }

        $method = $action['callback_name'];

        return $this->$method();
    }
}