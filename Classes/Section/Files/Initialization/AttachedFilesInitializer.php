<?php


namespace Classes\Section\Files\Initialization;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\File as FileEx;

use Lib\Files\Mappings\RequiredMappingsSetter;
use Lib\Files\Initialization\Initializer as MainInitializer;


/**
 * Предназначен для инициализации прикрепленных файлов к замечаниям
 *
 */
class AttachedFilesInitializer extends MainInitializer
{

    /**
     * Массив id замечаний
     *
     */
    private array $commentIds;


    /**
     * Конструктор класса
     *
     * @param int[] $commentIds массив id замечаний
     * @param int $typeOfObjectId id вида объекта
     * @throws FileEx
     */
    public function __construct(array $commentIds, int $typeOfObjectId)
    {
        if ($typeOfObjectId == 1) {
            $mapping_level_1 = 2;
            $mapping_level_2 = 1;
        } else {
            $mapping_level_1 = 2;
            $mapping_level_2 = 2;
        }

        $requiredMappingsSetter = new RequiredMappingsSetter();
        $requiredMappingsSetter->setMappingLevel2($mapping_level_1, $mapping_level_2);

        $this->commentIds = $commentIds;

        parent::__construct($requiredMappingsSetter);
    }


    /**
     * Реализация абстрактного метода
     *
     * @param string $fileClassName
     * @return array|null
     * @throws DataBaseEx
     */
    protected function getFiles(string $fileClassName): ?array
    {

        $mapping = [
            FILE_TABLE_MAPPING[2][1] => '\Tables\Comment\AttachedFiles\documentation_1',
            FILE_TABLE_MAPPING[2][2] => '\Tables\Comment\AttachedFiles\documentation_2'
            ];
        $class = $mapping[$fileClassName];
        $method = 'getAllAssocFileByIdsMainDocument';

        return $class::$method($this->commentIds);
    }
}