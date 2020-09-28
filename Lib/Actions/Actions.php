<?php


namespace Lib\Actions;
use Lib\Exceptions\DataBase as DataBaseEx;


/**
 * Предоставляет интерфейс для дочерних методов действий по определенному типу документа
 *
 */
abstract class Actions
{

    protected string $actionTable;
    protected string $accessClass;
    protected string $executionClass;


    /**
     * Предназначен для получения ассоциативных массивов активных действий
     *
     * @return array
     * @throws DataBaseEx
     */
    public function getAssocActiveActions(): array
    {
        return $this->actionTable::getAllAssocWhereActive();
    }


    /**
     * Предназначен для получения ассоциативного массива активного действия по имени страницы
     *
     * @param string $pageName
     * @return array|null <b>array</b> ассоцивтивный массив действия<br/>
     * <b>null</b> действие не существует
     * @throws DataBaseEx
     */
    public function getAssocActiveActionByPageName(string $pageName): ?array
    {
        return $this->actionTable::getAssocWhereActiveByPageName($pageName);
    }


    /**
     * Предназначен для получения объекта доступа к действиям
     *
     * @return AccessActions
     */
    public function getAccessActions(): AccessActions
    {
        return new $this->accessClass($this);
    }


    /**
     * Предназначен для получения объекта выполнений действий
     *
     * @return ExecutionActions
     */
    public function getExecutionActions(): ExecutionActions
    {
        return new $this->executionClass($this);
    }


    /**
     * Предназначен для определения названий классов:
     * - actionTable таблица действий над нужным документом
     * - accessClass класс проверки доступа к документа (наследуется от {@see \Lib\Actions\AccessActions}
     * - executionClass класс исполнений действий для документа (наследуется от {@see \Lib\Actions\ExecutionActions}
     *
     */
    abstract protected function defineClasses(): void;
}