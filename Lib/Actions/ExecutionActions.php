<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;
use Lib\Singles\PrimitiveValidator;
use Lib\Exceptions\PrimitiveValidator as PrimitiveValidatorEx;


// Предназначен для исполнения действия
//
abstract class ExecutionActions
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


    // Предназначен для проверки существования активного метода выполения действия по имени странцы
    // Принимает параметры-----------------------------------
    // pageName string : URN требуемой страницы
    //
    public function checkIssetCallbackByPageName(string $pageName): void
    {
        // Получаем только активное действие
        $action = $this->actions->getAssocActiveActionByPageName($pageName);
        $this->getValidatedCallback($action, $pageName);
    }


    // Предназначен для выполнения метода действия по имени страницы
    // Принимает параметры-----------------------------------
    // pageName string : URN требуемой страницы
    // Возвращает параметры----------------------------------
    // string : URN страницы, на которую необходимо перенаправить пользователя после действия
    //
    public function executeCallbackByPageName(string $pageName): string
    {
        // Получаем любое действие, в т.ч. неактивное, т.к. до захода
        // на страницу выполнения действия оно могло быть активным
        $action = $this->actions->getAssocActionByPageName($pageName);

        $method = $this->getValidatedCallback($action, $pageName);

        return $this->$method();
    }


    // Предназначен для получения проверенного метода действия
    // Принимает параметры-----------------------------------
    // action   ?array : массив действия, если оно существует
    // pageName string : имя страницы для вывода в сообщение об ошибке
    // Возвращает параметры----------------------------------
    // string : название метода действия
    // Выбрасывает исключения--------------------------------
    // Lib\Exceptions\Actions :
    // code:
    //  2 - попытка получить доступ к несуществующему действию для страницы
    //  3 - метод исполнения действия не реализован в дочернем классе
    //  4 - ошибка метода исполнения действия для страницы (объявленный тип возвращаемого значения не string)
    //
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
}