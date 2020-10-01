<?php


namespace Classes\Application;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as TablesEx;
use Tables\FinancingSources\type_1;
use Tables\FinancingSources\type_2;
use Tables\FinancingSources\type_3;
use Tables\FinancingSources\type_4;


/**
 * Обобщающий класс для работы с источниками финансирования к заявлению
 *
 */
final class FinancingSources
{

    /**
     * id заявления
     *
     */
    private int $applicationId;


    /**
     * Конструктор класса
     *
     * @param int $applicationId id заявления
     */
    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;
    }


    /**
     * Предназначен для получения источников финансирования к заявлению по его id
     *
     * @return array
     * @throws DataBaseEx
     * @throws TablesEx
     */
    public function getFinancingSources(): array
    {
        return [
            'type_1' => type_1::getAllAssocByIdMainDocument($this->applicationId),
            'type_2' => type_2::getAllAssocByIdMainDocument($this->applicationId),
            'type_3' => type_3::getAllAssocByIdMainDocument($this->applicationId),
            'type_4' => type_4::getAllAssocByIdMainDocument($this->applicationId)
        ];
    }
}