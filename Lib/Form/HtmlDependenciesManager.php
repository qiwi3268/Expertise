<?php


namespace Lib\Form;


/**
 * Предоставляет интерфейс дочерним классам для инициализации и предоставления
 * зависимостей в html-форме
 *
 */
abstract class HtmlDependenciesManager
{

    /**
     *
     * JSON_TRUE_OR (значения разделяются символом #) - зависимый блок отработает в том случае,
     * если в input'е хотя бы одно из перечисленных значений TRUE
     *
     * JSON_FALSE_AND (значения разделяются символом #) - зависимый блок отработает в том случае,
     * если в input'е одновремено все перечисленные значения FALSE
     *
     */

    /**
     * Зависимости отображения блоков
     *
     */
    protected array $blockDependencies = [];

    /**
     * Зависимости обязательности блоков
     *
     */
    protected array $requireDependencies = [];


    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        $this->initializeBlockDependencies();
        $this->initializeRequireDependencies();
    }


    /**
     * Предназначен для получения зависимостей отображения
     *
     * @return array
     */
    public function getBlockDependencies(): array
    {
        return $this->blockDependencies;
    }


    /**
     * Предназначен для получения зависимостей обязательности
     *
     * @return array
     */
    public function getRequireDependencies(): array
    {
        return $this->requireDependencies;
    }


    /**
     * Предназначен для установки зависимостей отображения
     *
     */
    abstract protected function initializeBlockDependencies(): void;


    /**
     * Предназначен для установки зависимостей обязательности
     *
     */
    abstract protected function initializeRequireDependencies(): void;
}