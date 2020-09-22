<?php


namespace Classes\RouteCallbacks;


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use core\Classes\Session;
use Lib\AccessToDocument\AccessToDocument;
use Lib\AccessToDocument\Locator;


/**
 * Предназначен для проверки доступа пользователя к документу, на который переходит пользователь
 *
 * Для работы класса должны быть определены константы:
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 *
 */
class AccessToDocumentChecker
{

    /**
     * Экземпляр класса проверки доступа пользователя к документу
     *
     */
    private AccessToDocument $accessToDocument;


    /**
     * Явный конструктор класса
     *
     * Необходим, так как класс создается до того, как будет вызван метод, объявляющий константы
     *
     * @throws AccessToDocumentEx
     */
    public function construct(): void
    {
        $this->accessToDocument = Locator::getInstance(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID)->getObject();
    }


    /**
     * Предназначен для проверки доступа пользователя к документу
     *
     * В случае отсутствия доступа - перенаправляет на навигационную страницу
     * с сообщением об ошибке
     *
     * @throws DataBaseEx
     */
    public function checkAccessToDocument(): void
    {
        try {

            $this->accessToDocument->checkAccess();
        } catch (AccessToDocumentEx $e) {

            Session::setErrorMessage("Документ, на который Вы собираетесь перейти недоступен. Код ошибки: '{$e->getCode()}'");
            header('Location: /home/navigation');
            exit();
        }
    }
}