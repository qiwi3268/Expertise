<?php


namespace Lib\Form;


abstract class DisplayDependencies
{

    protected array $blockDependencies = [];

    protected array $requireDependencies = [];


    public function __construct()
    {
        $this->initializeBlockDependencies();
        $this->initializeRequireDependencies();
    }


    public function getBlockDependencies(): array
    {
        return $this->blockDependencies;
    }

    public function getRequireDependencies(): array
    {
        return $this->requireDependencies;
    }

    /**
     * Предназначен для установки зависимостей отображения
     */
    abstract protected function initializeBlockDependencies(): void;


    /**
     * Предназначен для установки зависимостей обязательности
     *
     */
    abstract protected function initializeRequireDependencies(): void;
}