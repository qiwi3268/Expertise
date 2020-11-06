<?php


namespace core\Classes\ControllersInterface;


/**
 * Предоставляет индерфейс для промежуточных классов контроллеров
 *
 */
abstract class Controller
{


    /**
     * Прослойка перед вызовом основного метода выполнения контроллера
     *
     * <b>*</b> Предназначен для вызова из контекста RoutesXMLHandler.<br>
     *
     * Предназначен для реализации в промежуточном контроллере.<br>
     *
     * <b>*</b> Должен вызвать метод реализующего класса:
     * {@see \core\Classes\ControllersInterface\Controller::doExecute()}<br>
     * (согласно паттерну <i>Template Method</i>)
     */
    abstract public function execute(): void;


    /**
     * Основной метод выполнения контроллера
     *
     */
    abstract public function doExecute(): void;
}