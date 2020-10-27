<?php


namespace Conclusion\ConvertingData;

use Conclusion\ReceivingData\XMLDataRecipient;


/**
 * Предназначен для преобразования полученных данных для формирования XML-заключения
 *
 */
class XMLDataConverter extends DataConverter
{

    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        $this->dataRecipient = new XMLDataRecipient();

        parent::__construct();
    }


    /**
     * Реализация абстрактного метода
     *
     * @return array
     */
    public function getData(): array
    {
        return  [];
    }
}