<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;
use Lib\Singles\PrimitiveValidator;
use Tables\Exceptions\Exception;


/**
 * Предоставляет интерфейс для дочерних классов исполнений действий
 *
 */
abstract class ExecutionActions
{

    protected array $clearPOST;
    protected array $clearGET;
    protected PrimitiveValidator $primitiveValidator;


    /**
     * Имя вызывающего дочернего класса для отладки
     *
     */
    private string $childClassName;

    /**
     * Объект дочернего класса документа, унаследованного от библиотчечного класса
     *
     */
    private Actions $actions;


    /**
     * Конструктор класса
     *
     * @param Actions $actions
     */
    public function __construct(Actions $actions)
    {
        $this->clearPOST = clearHtmlArr($_POST);
        $this->clearGET = clearHtmlArr($_GET);
        $this->primitiveValidator = new PrimitiveValidator();

        $this->childClassName = static::class;
        $this->actions = $actions;
    }


    /**
     * Предназначен для проверки существования активного метода выполения действия по имени странцы
     *
     * @param string $pageName URN требуемой страницы
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function checkIssetCallbackByPageName(string $pageName): void
    {
        // Получаем только активное действие
        $action = $this->actions->getAssocActiveActionByPageName($pageName);
        $this->getValidatedCallback($action, $pageName);
    }


    /**
     * Предназначен для выполнения метода действия по имени страницы
     *
     * @param string $pageName URN требуемой страницы
     * @return string URN страницы, на которую необходимо перенаправить пользователя после действия
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function executeCallbackByPageName(string $pageName): string
    {
        // Получаем любое действие, в т.ч. неактивное, т.к. до захода
        // на страницу выполнения действия оно могло быть активным
        $action = $this->actions->getAssocActionByPageName($pageName);

        $method = $this->getValidatedCallback($action, $pageName);

        return $this->$method();
    }


    /**
     * Предназначен для получения проверенного метода действия
     *
     * @param array|null $action <b>array</b> ассоциативный массив действия<br/><b>null</b> действие не существует
     * @param string $pageName имя страницы для вывода в сообщение об ошибке
     * @return string название метода действия
     * @throws SelfEx
     * @throws ReflectionException
     */
    private function getValidatedCallback(?array $action, string $pageName): string
    {
        if (is_null($action)) {
            throw new SelfEx("Попытка получить доступ к несуществующему действию для страницы: '{$pageName}'", 2);
        }

        $method = $action['callback_name'];

        if (!method_exists($this, $method)) {
            throw new SelfEx("Метод исполнения действия: '{$this->childClassName}::{$method}' для страницы: '{$pageName}' не реализован в дочернем классе: '{$this->childClassName}'", 3);
        }

        $primitiveValidator = new PrimitiveValidator();

        try {
            $primitiveValidator->validateReturnType([$this, $method], 'string');
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Ошибка метода исполнения действия для страницы: '{$pageName}'. {$e->getMessage()}", 4);
        }

        return $method;
    }


    /**
     * Предназначен для получения значения обязательного параметра POST запроса
     *
     * @param string $key ключ параметра из суперглобального массива POST
     * @return mixed
     * @throws SelfEx
     */
    protected function getRequiredPOSTParameter(string $key)
    {
        if (!isset($this->clearPOST[$key])) {
            throw new SelfEx("Нет обязательного параметра POST запроса: '{$key}'", 5);
        }
        return $this->clearPOST[$key];
    }
}