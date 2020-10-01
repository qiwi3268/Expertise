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

    abstract protected function initializeBlockDependencies(): void;
    abstract protected function initializeRequireDependencies(): void;
}