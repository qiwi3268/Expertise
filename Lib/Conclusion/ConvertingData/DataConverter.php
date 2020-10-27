<?php


namespace Conclusion\ConvertingData;

use Conclusion\ReceivingData\DataRecipient;


/**
 * Основной класс, предназначенный для преобразования полученных данных для формирования заключения
 *
 */
abstract class DataConverter
{

    protected DataRecipient $dataRecipient;


    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {

    }


    /**
     * Предназначен для получения преобразованных данных к заключению
     *
     * @return array
     */
    abstract public function getData(): array;
}