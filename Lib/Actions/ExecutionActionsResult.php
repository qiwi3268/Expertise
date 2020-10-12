<?php


namespace Lib\Actions;
use Lib\Exceptions\Actions as SelfEx;


/**
 * Предназначен для возврата методом исполнения действий.
 *
 * Далее конвертируется в json-строку через магический метод __toString
 * с целью отправки на клиентский js
 *
 */
final class ExecutionActionsResult
{

    /**
     * API result, соответствующий успешному завершению действий API
     *
     */
    private int $successAPIResult;

    /**
     * Ссылка для переадрисации после выполнения действия
     *
     */
    private string $ref;

    /**
     * Массив дополнительных параметров
     *
     */
    private array $additionalParameters = [];


    /**
     * Конструктор класса
     *
     * @param string $ref ссылка для переадрисации после выполнения действия
     * @param int $successAPIResult API result, соответствующий успешному завершению действий API
     */
    public function __construct(string $ref, int $successAPIResult = 19)
    {
        $this->ref = $ref;
        $this->successAPIResult = $successAPIResult;
    }


    /**
     * Предназначен для преобразования инкапсулированных свойств объекта в json-строку
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode([
            'result' => $this->successAPIResult,
            'ref'    => $this->ref,
            'add'    => $this->additionalParameters
        ]);
    }


    /**
     * Предназначен для установки значения в дополнительные параметры
     *
     * @param string $key ключ, по корому будет записано значение
     * @param mixed $value значение
     * @return $this экземпляр текущего класса для последующей цепочки вызовов
     * @throws SelfEx
     */
    public function addAdditionalParameter(string $key, $value): self
    {
        if (isset($this->additionalParameters[$key])) {
            throw new SelfEx("Указанный параметер по ключу: '{$key}' уже существует в массиве параметров", 4001);
        }

        $this->additionalParameters[$key] = $value;
        return $this;
    }
}