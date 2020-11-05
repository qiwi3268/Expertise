<?php


namespace Classes\RouteCallbacks;

use Lib\Exceptions\Actions as ActionsEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use ReflectionException;
use core\Classes\Session;
use Lib\Actions\Locator;
use Lib\Actions\AccessActions;
use Lib\Actions\ExecutionActions;


/**
 * Предназначен для проверки действия, на которое переходит пользователь
 *
 * Для работы класса должны быть определена константа:
 * - CURRENT_DOCUMENT_TYPE
 *
 */
class ActionChecker
{

    private AccessActions $accessActions;
    private ExecutionActions $executionActions;


    /**
     * Конструктор класса
     *
     * @throws ActionsEx
     * @throws DataBaseEx
     * @throws TablesEx
     * @throws DocumentTreeHandlerEx
     */
    public function __construct()
    {
        $actions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)->getObject();
        $this->accessActions = $actions->getAccessActions();
        $this->executionActions = $actions->getExecutionActions();
    }


    /**
     * Предназначен для проверки пользователя к текущему действию
     *
     * В случае отсутствия доступа - перенаправляет на навигационную страницу
     * с сообщением об ошибке
     *
     * В данном методе происходит именно перенаправление, а не выбрасывание исключения,
     * поскольку пользователь может попробовать перейти по старой ссылке в письме и т.д., т.е.
     * злого усмысла или ошибки в этом нет
     *
     * @throws ActionsEx
     * @throws ReflectionException
     * @throws DataBaseEx
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
     * @throws DataBaseEx
     */
    public function checkIssetExecutionCallback(): void
    {
        $this->executionActions->checkIssetCallbackByPageName(URN);
    }
}