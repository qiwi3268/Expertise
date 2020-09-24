<?php


namespace Lib\Signs\Mappings;

use Lib\Files\Mappings\FilesTableMapping;


/**
 * Класс проверки маппинга таблиц подписей
 *
 */
class SignsTableMapping extends FilesTableMapping
{

    private const NEEDED_INTERFACE = 'Tables\Signs\Interfaces\SignTable';
    private string $fileClass;


    /**
     * Конструктор класса
     *
     * <b>***</b> Предполагается, что перед использованием данного класса маппинги были проверены классом FilesTableMapping,
     * поэтому здесь считается, что они полностью корректны
     *
     * @param string $mappingLevel1 маппинг 1-го уровня константного массива FILE_TABLE_MAPPING
     * @param string $mappingLevel2 маппинг 2-го уровня константного массива FILE_TABLE_MAPPING
     */
    public function __construct(string $mappingLevel1, string $mappingLevel2)
    {
        // Получение названия класса таблицы файлов
        $fileClass = FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2];

        // Проверка существования класса таблицы подписей
        if (!isset(SIGN_TABLE_MAPPING[$fileClass])) {
            $this->errorCode = 1;
            $this->errorText = "Не существует соответствующего класса таблицы подписей к классу файловой таблицы: '{$fileClass}'";
            return;
        }

        // Получение названия класса таблицы подписей
        $signClass = SIGN_TABLE_MAPPING[$fileClass];

        // Проверка на существование указанного класса таблицы подписей
        if (!class_exists($signClass)) {
            $this->errorCode = 2;
            $this->errorText = "Указанный класс таблицы подписей: '{$signClass}' не существует";
            return;
        }

        // Проверка на реализацию интерфейса
        if (
            !($interfaces = class_implements($signClass))
            || !in_array(self::NEEDED_INTERFACE, $interfaces, true)
        ) {
            $this->errorCode = 3;
            $this->errorText = "Указанный в маппинге класс: '{$signClass}' не реализует требуемый интерфейс: '" . self::NEEDED_INTERFACE . "'";
            return;
        }

        $this->fileClass = $fileClass;
        $this->class = $signClass;
    }


    /**
     * Предназначен для получения названия класса из таблицы файлов
     *
     * @return string имя таблицы файлов
     */
    public function getFileClassName(): string
    {
        return $this->fileClass;
    }
}

