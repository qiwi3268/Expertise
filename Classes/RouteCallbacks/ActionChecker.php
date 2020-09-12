<?php


namespace Classes\RouteCallbacks;
use core\Classes\Session;
use Lib\Actions\Locator;
use Lib\Actions\Actions;
use Lib\Actions\AccessActions;
use Lib\Actions\ExecutionActions;



class ActionChecker
{

    private Actions $actions;
    private AccessActions $accessActions;
    private ExecutionActions $executionActions;


    // Явный конструктор, т.к. класс создается до того, как будет вызван метод,
    // объявляющий константы
    public function construct(): void
    {
        $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getActions();
        $this->actions = $actions;
        $this->accessActions = $actions->getAccessActions();
        $this->executionActions = $actions->getExecutionActions();
    }


    public function checkAccess(): void
    {
        if (!$this->accessActions->checkAccessFromActionByPageName(URN)) {
            Session::setErrorMessage('В настоящий момент Вам недоступно выбранное действие');
            header('Location: /home/navigation');
            exit();
        }
    }


    public function checkIssetExecutionCallback(): void
    {
        $this->executionActions->checkIssetCallbackByPageName(URN);
    }
}