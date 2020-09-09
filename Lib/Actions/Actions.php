<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;


abstract class Actions
{

    private int $documentId;

    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  5 - отсутствует обязательный параметер GET / POST запроса: 'id_document'
    //
    public function __construct()
    {
        if (checkParamsGET('id_document')) {
            $this->documentId = clearHtmlArr($_GET)['id_document'];
        } elseif (checkParamsPOST('id_document')) {
            $this->documentId = clearHtmlArr($_POST)['id_document'];
        } else {
            throw new SelfEx("Отсутствует обязательный параметер GET / POST запроса: 'id_document'", 5);
        }

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