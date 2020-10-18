<?php


namespace Classes\Section\Actions;

use Lib\Form\HtmlDependenciesManager;


/**
 * Предназначен для инициализации и предоставления зависимостей
 * в html-форме действия "Создать описательную часть" | "Редактировать описательную часть"
 *
 */
class HtmlDependenciesManagerAction1 extends HtmlDependenciesManager
{

    /**
     * Реализация абстрактного метода
     *
     */
    protected function initializeBlockDependencies(): void
    {
        $this->blockDependencies = [
            'comment_criticality' => [
                1 => ['normative_document' => false],
                2 => ['normative_document' => true],
                3 => ['normative_document' => true]
            ],
        ];
    }


    /**
     * Реализация абстрактного метода
     *
     */
    protected function initializeRequireDependencies(): void
    {
        return;
    }
}