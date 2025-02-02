<?php


namespace Lib\Singles;

use Exception;


/**
 * Предназначеный для передачи переменных между блоками
 *
 * Паттерн: <i>Singleton, Registry</i>
 *
 */
class VariableTransfer
{

    /**
     * Сущность класса
     *
     */
    static private self $instance;

    /**
     * Режим работы класса
     *
     * <b>true</b> : жеский режим работы - попытки получить от get'тера несуществующий ключ вызывают <i>exception</i><br>
     * <b>false</b> : мягкий режим работы - попытки получить от get'тера несуществующий ключ возвращают <i>null</i>
     *
     */
    private bool $isHardMode;

    // Контейнеры для хранения

    /**
     * Флаги существования значений
     *
     */
    private array $existenceFlags = [];

    /**
     * Значения
     *
     */
    private array $values = [];

    /**
     * Конструктор класса
     *
     */
    private function __construct()
    {
        $this->isHardMode = true;
    }


    /**
     * Предназначен для получения сущности класса
     *
     * @return static сущность класса
     */
    static public function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Предназначен для установки жесткого режима работы
     *
     */
    public function setHardMode(): void
    {
        $this->isHardMode = true;
    }


    /**
     * Предназначен для установки мягкого режима работы
     *
     */
    public function setSoftMode(): void
    {
        $this->isHardMode = false;
    }


    /**
     * Предназначен для проверки существования ключа в указанном массиве при жестком режиме работы
     *
     * Возможно переопределить текущий режим работы для конкретного вызова, указав в ключе:<br>
     * <b>%S</b><i>(soft)</i> или <b>%H</b><i>(hard)</i>
     *
     * @param array $container контейнер для хранения значений
     * @param string $key ключ массива. Из значения будет вырезан режим работы, если он указан
     * @return $this
     * @throws Exception
     */
    private function checkIssetVariable(array $container, string &$key): self
    {
        $res = getHandlePregMatch("/^(.*)(%H|%S)?$/Uu", $key, false);

        $key = $res[1];
        $isHardMode = isset($res[2]) ? ($res[2] == '%H') : $this->isHardMode;

        if ($isHardMode && !isset($container[$key])) {
            throw new Exception("Ключ '{$key}' не существует в запрашиваемом контейнере");
        }
        return $this;
    }


    /**
     * Предназначен для установки флага существования
     *
     * @param string $key
     * @param bool $value
     */
    public function setExistenceFlag(string $key, bool $value): void
    {
        $this->existenceFlags[$key] = $value;
    }


    /**
     * Предназначен для получения флага существования
     *
     * @param string $key
     * @return bool|null <b>bool</b> результат флага существования, если он был установлен<br>
     * <b>null</b> запращиваемый флаг не был установлен
     * @throws Exception
     */
    public function getExistenceFlag(string $key): ?bool
    {
        return $this->checkIssetVariable($this->existenceFlags, $key)->existenceFlags[$key] ?? null;
    }


    /**
     * Предназначен для установки значений
     *
     * @param string $key
     * @param mixed $value
     */
    public function setValue(string $key, $value): void
    {
        $this->values[$key] = $value;
    }


    /**
     * Предназначен для получения значений
     *
     * @param string $key
     * @return mixed|null <b>mixed</b> значение, если оно было установлено<br>
     * <b>null</b> запращиваемое значение не было установлено
     * @throws Exception
     */
    public function getValue(string $key)
    {
        return $this->checkIssetVariable($this->values, $key)->values[$key] ?? null;
    }
}