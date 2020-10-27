<?php


namespace Conclusion\ConvertingData;

use Conclusion\ReceivingData\WebDataRecipient;


/**
 * Предназначен для преобразования полученных данных для формирования Web-заключения
 *
 */
class WebDataConverter extends DataConverter
{

    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        $this->dataRecipient = new WebDataRecipient();

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