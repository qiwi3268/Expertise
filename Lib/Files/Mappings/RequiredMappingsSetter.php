<?php


namespace Lib\Files\Mappings;

use Lib\Exceptions\File as SelfEx;


/**
 * Класс предназначен для создания структуры нужных маппингов, по которой в дальнейшем классы будут получать файлы
 *
 */
class RequiredMappingsSetter
{

    /**
     * Массив нужных маппингов файловых таблиц
     *
     */
    private array $mappings;


    /**
     * Предназначен для установки всех маппингов 2 уровня, находящихся в указанном маппинге 1 уровня
     *
     * @param int $mapping_level_1 индекс массива FILE_TABLE_MAPPING
     * @throws SelfEx
     */
    public function setMappingLevel1(int $mapping_level_1): void
    {
        $this->checkMappingLevel1Exist($mapping_level_1);

        foreach (FILE_TABLE_MAPPING[$mapping_level_1] as $mapping_level_2_code => $className) {
            $this->mappings[$mapping_level_1][$mapping_level_2_code] = $className;
        }
    }


    /**
     * Предназначен для установки конкретного маппинга 2 уровня, находящегося в указанном маппинге 1 уровня
     *
     * @param int $mapping_level_1 индекс массива FILE_TABLE_MAPPING
     * @param int $mapping_level_2 индекс массива FILE_TABLE_MAPPING[mapping_level_1]
     * @throws SelfEx
     */
    public function setMappingLevel2(int $mapping_level_1, int $mapping_level_2): void
    {
        $this->checkMappingLevel2Exist($mapping_level_1, $mapping_level_2);
        $this->mappings[$mapping_level_1][$mapping_level_2] = FILE_TABLE_MAPPING[$mapping_level_1][$mapping_level_2];
    }


    /**
     * Предназначен для получения массива нужных маппингов
     *
     * @return array нужные маппинги
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }


    /**
     * Предназначен для проверки существования указанного маппинга 1 уровня
     *
     * @param int $mapping_level_1 индекс массива FILE_TABLE_MAPPING
     * @throws SelfEx
     */
    private function checkMappingLevel1Exist(int $mapping_level_1): void
    {
        if (!array_key_exists($mapping_level_1, FILE_TABLE_MAPPING)) {
            throw new SelfEx("Запрашиваемый mapping_level_1: '{$mapping_level_1}' не существует в FILE_TABLE_MAPPING");
        }
    }


    /**
     * Предназначен для проверки существования указанного маппинга 2 уровня
     *
     * @param int $mapping_level_1 индекс массива FILE_TABLE_MAPPING
     * @param int $mapping_level_2 индекс массива FILE_TABLE_MAPPING[mapping_level_1]
     * @throws SelfEx
     */
    private function checkMappingLevel2Exist(int $mapping_level_1, int $mapping_level_2): void
    {
        $this->checkMappingLevel1Exist($mapping_level_1);
        if (!array_key_exists($mapping_level_2, FILE_TABLE_MAPPING[$mapping_level_1])) {
            throw new SelfEx("Запрашиваемый mapping_level_2: '{$mapping_level_2}' не существует в mapping_level_1: '{$mapping_level_1}' в FILE_TABLE_MAPPING");
        }
    }
}