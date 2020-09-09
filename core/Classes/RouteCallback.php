<?php


namespace core\Classes;
use Classes\Exceptions\PregMatch as PregMatchEx;
use core\Classes\Session;
use Lib\Actions\Locator as ActionLocator;


class RouteCallback
{
    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }


    public function handleActionURI(): void
    {
        $pattern = "/\A\/home\/([a-z]+)\/actions\/action_(\d+)\?id_document=(\d+)\z/iu";

        list(1 => $documentType, 2 => $actionId, 3 => $documentId) = getHandlePregMatch($pattern, $this->route->getURI(), false);

        define('CURRENT_DOCUMENT_TYPE', DOCUMENT_TYPE[$documentType]);
        define('CURRENT_ACTION_ID', (int)$actionId);
        define('CURRENT_DOCUMENT_ID', (int)$documentId);
    }


    // Предназначен для проверки доступа к дейстувию
    //
    public function checkAccessToAction(): void
    {
        $URN = $this->route->getURN();

        $actions = ActionLocator::getInstance('application')->getActions();

        $accessActions = $actions->getAccessActions();
        $executionActions = $actions->getExecutionActions();

        if (!$accessActions->checkAccessFromActionByPageName($URN)) {
            Session::setErrorMessage("Действие по странице: '{$URN}' недоступно");
            header('Location: /home/navigation');
            exit();
        }

        $executionActions->checkIssetCallbackByPageName($URN);
    }
}