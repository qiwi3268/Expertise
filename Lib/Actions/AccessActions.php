<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Singles\PrimitiveValidator;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;


// Предназначен для проверки доступа пользователя к действиям
//
abstract class AccessActions
{

    private string $childClassName; // Имя вызывающего дочернего класса для отладки
    private Actions $actions;


    // Принимает параметры-----------------------------------
    // actions Actions : объект дочернего класса документа, унаследованного от библиотчечного класса Lib\Actions\Actions
    //
    public function __construct(Actions $actions)
    {
        $this->childClassName = static::class;
        $this->actions = $actions;
    }


    // Предназначен для получения ассоциативных массивов доступных действий
    // То есть тех действий, для которых callback вернул true
    // Возвращает параметры----------------------------------
    // array : ассоциативные массивы доступных действий
    //
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


    // Предназначен для проверки доступа к действию по имени страницы
    // Принимает параметры-----------------------------------
    // pageName string : URN требуемой страницы
    // Возвращает параметры----------------------------------
    // true  : есть доступ к действию
    // false : нет доступа к действию
    //
    public function checkAccessFromActionByPageName(string $pageName): bool
    {
        $action = $this->actions->getAssocActiveActionByPageName($pageName);

        return $this->getValidatedCallbackResult($action, $pageName);
    }


    // Предназначен для получения проверенного результата callback'а
    // Принимает параметры-----------------------------------
    // action   ?array : массив действия, если оно существует
    // pageName string : имя страницы для вывода в сообщение об ошибке
    // Возвращает параметры----------------------------------
    // bool : результат callback'а
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  2 - попытка получить доступ к несуществующему действию для страницы
    //  3 - метод доступа к действию не реализован в дочернем классе
    //  4 - ошибка метода доступа к действию для страницы (объявленный тип возвращаемого значения не bool)
    //
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