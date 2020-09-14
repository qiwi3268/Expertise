<?php


namespace Lib\Miscs\Initialization;


/**
 * Абстрактный класс, предназначеный для инициализации справочников в форме
 *
 * Предоставляет потомкам инкапсулированные свойства и интерфейс (методы) для инициализации
 * Потомки в методе-конструкторе должны заполнить через методы
 * {@see Initializer::setSingleMisc()} и {@see Initializer::setDependentMisc()} нужные им справочники
 *
 */
abstract class Initializer
{

    /**
     * Массив одиночных справочников
     *
     */
    private array $singleMiscs = [];

    /**
     * Массив зависимых справочников
     *
     */
    private array $dependentMiscs = [];


    /**
     * Предназначен для разбивки одиночных справочников по страницам
     *
     * @return array разбитые постранично справочники
     * @throws \LogicException
     */
    public function getPaginationSingleMiscs(): array
    {
        if (empty($this->singleMiscs)) throw new \LogicException("Вызван метод Initializer::getPaginationSingleMiscs при пустом массиве singleMiscs");

        $result = [];

        foreach ($this->singleMiscs as $miscName => $misc) {
            $result[$miscName] = array_chunk($misc, static::PAGINATION_SIZE, false);
        }
        return $result;
    }


    /**
     * Предназначен для разбивки зависимых справочников по страницам
     *
     * @return array разбитые постранично справочники
     * @throws \LogicException
     */
    public function getPaginationDependentMiscs(): array
    {
        if (empty($this->dependentMiscs)) throw new \LogicException("Вызван метод Lib\Miscs\Initialization\Initializer::getPaginationDependentMiscs при пустом массиве dependentMiscs");

        $result = [];

        foreach ($this->dependentMiscs as $miscName => $mainMiscIds) {

            // Цикл по справочнику в зависимоти от id-главного справочника
            foreach ($mainMiscIds as $mainMiscId => $misc) {

                $result[$miscName][$mainMiscId] = array_chunk($misc, static::PAGINATION_SIZE, false);
            }
        }
        return $result;
    }


    /**
     * Предназначен для установки одиночного справочника
     *
     * @param string $name название справочника
     * @param array $misc справочник
     */
    protected function setSingleMisc(string $name, array $misc): void
    {
        $this->singleMiscs[$name] = $misc;
    }


    /**
     * Предназначен для установки зависимого справочника
     *
     * @param string $name название справочника
     * @param array $misc справочник
     */
    protected function setDependentMisc(string $name, array $misc): void
    {
        $this->dependentMiscs[$name] = $misc;
    }


    /**
     * Абстрактный конструктор класса
     *
     * Предназначен для инициализации справочников через методы {@see Initializer::setSingleMisc()} и {@see Initializer::setDependentMisc()}
     *
     */
    abstract public function __construct();
}