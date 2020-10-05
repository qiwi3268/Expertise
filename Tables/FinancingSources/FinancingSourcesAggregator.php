<?php


namespace Tables\FinancingSources;

use Lib\Exceptions\DataBase as DataBaseEx;
use Tables\Exceptions\Tables as SelfEx;


/**
 * Обобщающий класс для работы с источниками финансирования
 *
 */
final class FinancingSourcesAggregator
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
            throw new SelfEx("Тип таблицы: '{$tableType}' не определен", 5001);
        }
        $this->tableType = $tableType;
        $this->mainDocumentId = $mainDocumentId;
    }


    /**
     * Предназначен для получения всех источников финансирования
     *
     * @return array
     * @throws DataBaseEx
     * @throws SelfEx
     */
    public function getFinancingSources(): array
    {
        $result = [];

        for ($i = 1; $i <= 4; $i++) {

            $type = "type_{$i}";

            $result[$type] = call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\{$type}",
                    'getAllAssocByIdMainDocument'
                ], $this->mainDocumentId
            );
        }
        return $result;
    }


    /**
     * Поочереди вызывает методы удаления всех записей источников финансирования
     *
     * @throws DataBaseEx
     */
    public function deleteAll(): void
    {
        for ($i = 1; $i <= 4; $i++) {

            call_user_func(
                [
                    "\Tables\FinancingSources\\{$this->tableType}\\type_{$i}",
                    'deleteAllByIdMainDocument'
                ],
                $this->mainDocumentId
            );
        }
    }


    /**
     * Предназначен для создания записи источника финансирования "Бюджетные средства"
     *
     * Вызвает {@see \Tables\FinancingSources\application\type_1}<br>
     * или<br>
     * {@see \Tables\FinancingSources\common_part\type_1}
     *
     * @param int $id_main_document
     * @param int|null $id_budget_level
     * @param int $no_data
     * @param int|null $percent
     * @return int
     * @throws DataBaseEx
     */
    public function createType1(
        int $id_main_document,
        ?int $id_budget_level,
        int $no_data,
        ?int $percent
    ): int {

        return call_user_func(
            [
                "\Tables\FinancingSources\\{$this->tableType}\\type_1",
                'create'
            ],
            $id_main_document,
            $id_budget_level,
            $no_data,
            $percent
        );
    }


    /**
     * Предназначен для создания записи источника финансирования "Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК"
     *
     * Вызвает {@see \Tables\FinancingSources\application\type_2}<br>
     * или<br>
     * {@see \Tables\FinancingSources\common_part\type_2}
     *
     * @param int $id_main_document
     * @param string|null $full_name
     * @param string|null $INN
     * @param string|null $KPP
     * @param string|null $OGRN
     * @param string|null $address
     * @param string|null $location
     * @param string|null $telephone
     * @param string|null $email
     * @param int $no_data
     * @param int|null $percent
     * @return int
     * @throws DataBaseEx
     */
    public function createType2(
        int $id_main_document,
        ?string $full_name,
        ?string $INN,
        ?string $KPP,
        ?string $OGRN,
        ?string $address,
        ?string $location,
        ?string $telephone,
        ?string $email,
        int $no_data,
        ?int $percent
    ): int {

        return call_user_func(
            [
                "\Tables\FinancingSources\\{$this->tableType}\\type_2",
                'create'
            ],
            $id_main_document,
            $full_name,
            $INN,
            $KPP,
            $OGRN,
            $address,
            $location,
            $telephone,
            $email,
            $no_data,
            $percent
        );
    }


    /**
     * Предназначен для создания записи источника финансирования "Собственные средства застройщика"
     *
     * Вызвает {@see \Tables\FinancingSources\application\type_3}<br>
     * или<br>
     * {@see \Tables\FinancingSources\common_part\type_3}
     *
     * @param int $id_main_document
     * @param int $no_data
     * @param int|null $percent
     * @return int
     * @throws DataBaseEx
     */
    public function createType3(
        int $id_main_document,
        int $no_data,
        ?int $percent
    ): int {

        return call_user_func(
            [
                "\Tables\FinancingSources\\{$this->tableType}\\type_3",
                'create'
            ],
            $id_main_document,
            $no_data,
            $percent
        );
    }


    /**
     * Предназначен для создания записи источника финансирования "Средства инвестора"
     *
     * Вызвает {@see \Tables\FinancingSources\application\type_4}<br>
     * или<br>
     * {@see \Tables\FinancingSources\common_part\type_4}
     *
     * @param int $id_main_document
     * @param int $no_data
     * @param int|null $percent
     * @return int
     * @throws DataBaseEx
     */
    public function createType4(
        int $id_main_document,
        int $no_data,
        ?int $percent
    ): int {

        return call_user_func(
            [
                "\Tables\FinancingSources\\{$this->tableType}\\type_4",
                'create'
            ],
            $id_main_document,
            $no_data,
            $percent
        );
    }


    /**
     * Предназначен для создания записей из декодированного и валидированного
     * из json-строки массива источников финансирования
     *
     * @param array $financingSources
     * @return array
     * @throws DataBaseEx
     */
    public function createByArray(array $financingSources): array
    {
        $ids = [];

        foreach ($financingSources as $source) {

            $no_data = is_null($source['no_data']) ? 0 : 1;

            switch ($source['type']) {

                case '1' :
                    $ids[] = $this->createType1(
                        $this->mainDocumentId,
                        $source['budget_level'],
                        $no_data,
                        $source['percent']
                    );
                    break;

                case '2' :
                    $ids[] = $this->createType2(
                        $this->mainDocumentId,
                        $source['full_name'],
                        $source['INN'],
                        $source['KPP'],
                        $source['OGRN'],
                        $source['address'],
                        $source['location'],
                        $source['telephone'],
                        $source['email'],
                        $no_data,
                        $source['percent']
                    );
                    break;

                case '3' :
                    $ids[] = $this->createType3(
                        $this->mainDocumentId,
                        $no_data,
                        $source['percent']
                    );
                    break;

                case '4' :
                    $ids[] = $this->createType4(
                        $this->mainDocumentId,
                        $no_data,
                        $source['percent']
                    );
                    break;
            }
        }
        return $ids;
    }
}