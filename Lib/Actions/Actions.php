<?php


namespace Lib\Actions;


/**
 * Предоставляет интерфейс для дочерних методов действий по определенному типу документа
 *
 */
abstract class Actions
{

    /**
     * Предназначен для получения ассоциативных массивов активных действий
     *
     * @return array
     */
    abstract public function getAssocActiveActions(): array;


    /**
     * Предназначен для получения ассоциативного массива активного действия по имени страницы
     *
     * @param string $pageName
     * @return array|null <b>array</b> ассоцивтивный массив действия<br/><b>null</b> действие не существует
     */
    abstract public function getAssocActiveActionByPageName(string $pageName): ?array;


    /**
     * Предназначен для получения ассоциативного массива действия по имени страницы
     *
     * @param string $pageName
     * @return array|null <b>array</b> ассоцивтивный массив действия<br/><b>null</b> действие не существует
     */
    abstract public function getAssocActionByPageName(string $pageName): ?array;


    /**
     * Предназначен для получения объекта доступа к действиям
     *
     * @return AccessActions
     */
    abstract public function getAccessActions(): AccessActions;


    /**
     * Предназначен для получения объекта выполнений действий
     *
     * @return ExecutionActions
     */
    abstract public function getExecutionActions(): ExecutionActions;
}