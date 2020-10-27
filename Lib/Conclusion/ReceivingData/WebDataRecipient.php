<?php


namespace Conclusion\ReceivingData;


/**
 * Предназначен для получения данных для формирования Web-заключения
 *
 */
class WebDataRecipient extends DataRecipient
{

    /**
     * Конструктор класса
     *
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Предназначен для получения информации с титульного листа
     *
     */
    public function getTitleInfo(): array
    {

    }
}