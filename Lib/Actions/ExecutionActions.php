<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;
use Lib\Singles\PrimitiveValidator;


/**
 * Предоставляет интерфейс для дочерних классов исполнений действий
 *
 */
abstract class ExecutionActions
{

    /**
     * Нет обязательного параметра POST / GET запроса
     * todo убрать
     */
    public const MISSING_PARAMS_CODE = 3004;

    /**
     * Ошибка во время исполнения действия
     *
     */
    public const ACTION_ERROR_CODE = 3005;

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
    protected Actions $actions;


    /**
     * Конструктор класса
     *
     * @param Actions $actions
     */
    public function __construct(Actions $actions)
    {
        $this->clearPOST = clearHtmlArr($_POST);
        $this->clearGET = clearHtmlArr($_GET);

        //todo тут переделать на реквесты

        $this->primitiveValidator = new PrimitiveValidator();

        $this->childClassName = static::class;
        $this->actions = $actions;
    }


    /**
     * Предназначен для проверки существования активного метода выполения действия по имени странцы
     *
     * @param string $pageName URN требуемой страницы
     * @throws DataBaseEx
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
     * Предназначен для выполнения активного метода действия по имени страницы
     *
     * @param string $pageName URN требуемой страницы
     * @return string URN страницы, на которую необходимо перенаправить пользователя после действия
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function executeCallbackByPageName(string $pageName): string
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        $method = $this->getValidatedCallback($action, $pageName);

        return $this->$method();
    }


    /**
     * Предназначен для получения проверенного метода действия
     *
     * @param array|null $action <b>array</b> ассоциативный массив действия<br/>
     * <b>null</b> действие не существует
     * @param string $pageName имя страницы для вывода в сообщение об ошибке
     * @return string название метода действия
     * @throws SelfEx
     * @throws ReflectionException
     */
    private function getValidatedCallback(?array $action, string $pageName): string
    {
        if (is_null($action)) {
            throw new SelfEx("Попытка получить доступ к несуществующему действию для страницы: '{$pageName}'", 3001);
        }

        $method = $action['callback_name'];

        if (!method_exists($this, $method)) {
            throw new SelfEx("Метод исполнения действия: '{$this->childClassName}::{$method}' для страницы: '{$pageName}' не реализован в дочернем классе: '{$this->childClassName}'", 3002);
        }

        $primitiveValidator = new PrimitiveValidator();

        try {
            $primitiveValidator->validateReturnType([$this, $method], 'Lib\Actions\ExecutionActionsResult');
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Ошибка метода исполнения действия для страницы: '{$pageName}'. {$e->getMessage()}", 3003);
        }

        return $method;
    }


    /**
     * Предназначен для проверки наличия требуемых параметров в <b>POST</b> запросе
     *
     * @uses checkParamsPOST()
     * @param string ...$params <i>перечисление</i> необходимых параметров
     * @throws SelfEx
     */
    protected function checkParamsPOST(string ...$params): void
    {
        if (!call_user_func_array('checkParamsPOST', $params)) {
            $debug = implode(', ', $params);
            throw new SelfEx("Нет одного или более обязательного параметра POST запроса: '{$debug}'", 3004);
        }
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
            throw new SelfEx("Нет обязательного параметра POST запроса: '{$key}'", 3004);
        }
        return $this->clearPOST[$key];
    }
}