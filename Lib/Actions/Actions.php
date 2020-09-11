<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;


abstract class Actions
{

    public function __construct()
    {
    }


    // Предназначен для получения ассоциативных массивов активных действий
    //
    abstract public function getAssocActiveActions(): array;

    // Предназначен для получения ассоциативного массива активного действия по имени страницы
    //
    abstract public function getAssocActiveActionByPageName(string $pageName): ?array;

    // Предназначен для получения ассоциативного массива действия по имени страницы
    //
    abstract public function getAssocActionByPageName(string $pageName): ?array;

    // Предназначен для получения объекта доступа к действиям
    //
    abstract public function getAccessActions(): AccessActions;

    // Предназначен для получения объекта выполнений действий
    //
    abstract public function getExecutionActions(): ExecutionActions;
}