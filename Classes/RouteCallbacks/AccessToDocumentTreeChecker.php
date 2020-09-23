<?php


namespace Classes\RouteCallbacks;


use Lib\Exceptions\AccessToDocument as AccessToDocumentEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use ReflectionException;

use core\Classes\Session;
use Lib\AccessToDocument\AccessToDocumentTree;


/**
 * Предназначен для проверки доступа пользователя к документу, на который
 * переходит пользователь, с учетом <b>всего дерева наследования</b> до нужного документа
 *
 * Для работы класса должны быть определены константы:
 * - CURRENT_DOCUMENT_TYPE
 * - CURRENT_DOCUMENT_ID
 *
 */
class AccessToDocumentTreeChecker
{

    /**
     * Экземпляр класса проверки доступа
     *
     */
    private AccessToDocumentTree $accessToDocumentTree;


    /**
     * Явный конструктор класса
     *
     * Необходим, так как класс создается до того, как будет вызван метод, объявляющий константы
     *
     * @throws DataBaseEx
     * @throws AccessToDocumentEx
     */
    public function construct(): void
    {
        $this->accessToDocumentTree = new AccessToDocumentTree(CURRENT_DOCUMENT_TYPE, CURRENT_DOCUMENT_ID);
    }


    /**
     * Предназначен для проверки доступа пользователя к документу
     *
     * В случае отсутствия доступа - перенаправляет на навигационную страницу
     * с сообщением об ошибке
     *
     * @throws DataBaseEx
     * @throws ReflectionException
     */
    public function checkAccessToDocumentTree(): void
    {
        try {

            $this->accessToDocumentTree->checkAccessToDocumentTree();
        } catch (AccessToDocumentEx $e) {

            Session::setErrorMessage("Документ, на который Вы собираетесь перейти недоступен. Код ошибки: '{$e->getCode()}'");
            header('Location: /home/navigation');
            exit();
        }
    }
}