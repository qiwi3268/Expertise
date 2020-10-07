<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Singles\PrimitiveValidator;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;
use ReflectionException;



/**
 * Предоставляет интерфейс для дочерних классов проверки доступа пользователя к действиям
 *
 */
abstract class AccessActions
{

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
     *
     */
    public function __construct(Actions $actions)
    {
        $this->childClassName = static::class;
        $this->actions = $actions;
    }


    /**
     * Предназначен для получения ассоциативных массивов доступных действий
     *
     * То есть тех действий, для которых callback вернул true
     *
     * @return array ассоциативные массивы доступных действий
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function getAvailableActions(): array
    {
        $result = [];

        foreach ($this->actions->getAssocActiveActions() as $action) {

            if ($this->getValidatedCallbackResult($action, $action['page_name'])) {
                $result[] = $action;
            }
        }
        return $result;
    }


    /**
     * Предназначен для проверки доступа к действию по имени страницы
     *
     * @param string $pageName URN требуемой страницы
     * @return bool <b>true</b> есть доступ к действию<br/>
     * <b>false</b> нет доступа к действию
     *
     * @throws DataBaseEx
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function checkAccessFromActionByPageName(string $pageName): bool
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        return $this->getValidatedCallbackResult($action, $pageName);
    }


    /**
     * Предназначен для получения проверенного результата callback'а
     *
     * @param array|null $action <b>array</b> ассоциативный массив действия<br/>
     * <b>null</b> действие не существует
     * @param string $pageName имя страницы для вывода в сообщение об ошибке
     * @return bool результат callback'а
     * @throws SelfEx
     * @throws ReflectionException
     */
    public function getValidatedCallbackResult(?array $action, string $pageName): bool
    {
        if (is_null($action)) {
            throw new SelfEx("Попытка получить доступ к несуществующему действию для страницы: '{$pageName}'", 2);
        }

        $method = $action['callback_name'];

        if (!method_exists($this, $method)) {
            throw new SelfEx("Метод доступа к действию: '{$this->childClassName}::{$method}' для страницы: '{$pageName}' не реализован в дочернем классе: '{$this->childClassName}'", 3);
        }

        $primitiveValidator = new PrimitiveValidator();

        try {
            $primitiveValidator->validateReturnType([$this, $method], 'bool');
        } catch (PrimitiveValidatorEx $e) {
            throw new SelfEx("Ошибка метода доступа к действию для страницы: '{$pageName}'. {$e->getMessage()}", 4);
        }

        return $this->$method();
    }
}