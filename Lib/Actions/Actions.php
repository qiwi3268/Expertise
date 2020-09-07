<?php


namespace Lib\Actions;

use Lib\Exceptions\Actions as SelfEx;


// Абстрактный класс для получения и проверки доступных действий у пользователя
//
abstract class Actions
{

    // Ассоциативный массив активных (is_active) действий из БД к текущему типу документа
    protected array $activeActionsAssoc;

    // Ассоциативный массив данных бизнесс-процесса, необходимых для работы callback-методов
    protected array $businessProcessAssoc;

    // Имя вызывающего дочернего класса
    protected string $childClassName;

    // Имя страницы и callback'а текущего (выбранного) действия.
    // null в случае, если никакое действие не выбрано, т.е. режим просмотра документа
    protected ?string $currentActionPageName = null;
    protected ?string $currentActionCallbackName = null;


    // Предназначен для проверки того, что в классе типа документа реализованы
    // все активные (is_active) callback-методы из БД
    //
    public function __construct()
    {

        // Запись свойства через дочерний метод
        $this->activeActionsAssoc = $this->getAssocActiveActions();

        // Имя вызывающего дочернего класса
        $this->childClassName = get_called_class();

        $missingCallbacks = [];

        foreach ($this->activeActionsAssoc as ['page_name' => $page_name, 'callback_name' => $callback_name]) {

            // Проверка наличия необходимых методов в дочернем классе
            if (!method_exists($this->childClassName, $callback_name)) {
                $missingCallbacks[] = $callback_name;
            }

            // Получение имени страницы и callback'а текущего действия
            if ($page_name == URN) {
                $this->currentActionPageName = $page_name;
                $this->currentActionCallbackName = $callback_name;
            }
        }

        if (!empty($missingCallbacks)) {

            $message = "В классе: '{$this->childClassName}' отсутствуют методы, присутствующие в БД: ";
            $message .= implode(', ', $missingCallbacks);

            throw new SelfEx($message, 2);
        }

        // Запись свойства через дочерний метод
        $this->businessProcessAssoc = $this->getAssocBusinessProcess();
    }


    // Предназначен для проверки доступа к текущему действию
    // Возвращает параметры----------------------------------
    // true  : есть доступ к действию
    // false : нет доступа к действию
    //
    public function checkAccessFromCurrentAction(): bool
    {

        $callbackName = $this->currentActionCallbackName;

        if (is_null($callbackName)) {

            // Существует 2 варианта, приводящих к данной ошибке:
            // 1 - свойство не указано в БД
            // 2 - попытка вызвать настойщий метод у страницы, не имеющей отношения к конкретному действию
            $message = 'Свойство currentActionCallbackName класса ActionsSidebar имеет значение null. При этом произошла попытка проверки доступа к текущему действию';
            throw new SelfEx($message, 3);
        }

        return $this->getCallbackResult($callbackName);
    }


    // Предназначен для получения ассоциативного массива всех доступных действий
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив доступных действий
    //
    public function getAvailableActions(): array
    {

        $result = [];

        foreach ($this->activeActionsAssoc as $action) {

            if ($this->getCallbackResult($action['callback_name'])) {

                $result[] = $action;
            }
        }

        return $result;
    }


    // Предназначен для получения результатов callback-метода с учетом проверки на возвращаемое значение
    // Возвращает параметры----------------------------------
    // bool : результат callback'а
    //
    private function getCallbackResult(string $name): bool
    {

        $result = $this->$name();

        if (!is_bool($result)) {

            $message = "Метод: {$this->childClassName}::{$name} возвращает значение, не принадлежащее типу boolean";
            throw new SelfEx($message, 4);
        }

        return $result;
    }


    // -----------------------------------------------------------------------------------------
    // Объявление абстрактных методов класса
    // -----------------------------------------------------------------------------------------

    // Предназначен для получения ассоциативного массива активных действий из БД для выбранного типа документа
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив действий
    //
    abstract protected function getAssocActiveActions(): array;

    // Предназначен для получения ассоциативного массива необходимых данных по бизнесс-процессу, для работы callback-методов
    // Возвращает параметры----------------------------------
    // array : ассоциативный массив данных
    //
    abstract protected function getAssocBusinessProcess(): array;
}