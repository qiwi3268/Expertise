<?php


namespace Classes\RouteCallbacks;

use Lib\Exceptions\Actions as ActionsEx;
use ReflectionException;
use core\Classes\Session;
use Lib\Actions\Locator;
use Lib\Actions\AccessActions;
use Lib\Actions\ExecutionActions;


/**
 *  Предназначен для проверки действия, на которое переходит пользователь
 *
 */
class ActionChecker
{

    private AccessActions $accessActions;
    private ExecutionActions $executionActions;


    /**
     * Явный конструктор класса
     *
     * Класс создается до того, как будет вызван метод, объявляющий константы
     *
     * @throws ActionsEx
     */
    public function construct(): void
    {
        $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getActions();
        $this->accessActions = $actions->getAccessActions();
        $this->executionActions = $actions->getExecutionActions();
    }


    /**
     * Предназначен для проверки пользователя к текущему действию
     *
     * В случае отсутствия доступа - перенаправляет на навигационную страницу
     * с сообщением об ошибке
     *
     * @throws ActionsEx
     */
    public function checkAccess(): void
    {
        if (!$this->accessActions->checkAccessFromActionByPageName(URN)) {
            Session::setErrorMessage('В настоящий момент Вам недоступно выбранное действие');
            header('Location: /home/navigation');
            exit();
        }
    }


    /**
     * Предназначен для проверки реализации callback'а исполнения действия
     *
     * @throws ActionsEx
     * @throws ReflectionException
     */
    public function checkIssetExecutionCallback(): void
    {
        $this->executionActions->checkIssetCallbackByPageName(URN);
    }
}