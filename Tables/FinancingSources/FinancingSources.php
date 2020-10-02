<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as SelfEx;


/**
 * Обобщающий класс для работы с источниками финансирования
 *
 */
final class FinancingSources
{

    public const APPLICATION_TABLE_TYPE = 'application';
    public const COMMON_PART_TABLE_TYPE = 'common_part';


    /**
     * Тип таблицы
     *
     */
    private string $tableType;

    /**
     * id главного документа
     *
     */
    private int $mainDocumentId;


    /**
     * Конструктор класса
     *
     * @param string $tableType тип таблицы
     * @param int $mainDocumentId id главного документа
     * @throws SelfEx
     */
    public function __construct(string $tableType, int $mainDocumentId)
    {
        if (
            $tableType != self::APPLICATION_TABLE_TYPE
            && $tableType != self::COMMON_PART_TABLE_TYPE
        ) {
            throw new SelfEx("Тип таблицы: '{$tableType}' не определен", 6001);
        }
        $this->tableType = $tableType;
        $this->mainDocumentId = $mainDocumentId;
    }


    /**
     * Предназначен для получения всех источников финансирования по id главного документа
     *
     * @return array
     * @throws DataBaseEx
     * @throws SelfEx
     */
    public function getFinancingSources(): array
    {
        $test = "\Tables\FinancingSources\\{$this->tableType}\\type_1";
        return [
            'type_1' => call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\type_1",
                    'getAllAssocByIdMainDocument'
                ], $this->mainDocumentId
            ),

            'type_2' => call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\type_2",
                    'getAllAssocByIdMainDocument'
                ], $this->mainDocumentId
            ),

            'type_3' => call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\type_3",
                    'getAllAssocByIdMainDocument'
                ], $this->mainDocumentId
            ),

            'type_4' => call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\type_4",
                    'getAllAssocByIdMainDocument'
                ], $this->mainDocumentId)
        ];
    }
}