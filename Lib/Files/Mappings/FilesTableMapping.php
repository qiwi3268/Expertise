<?php


namespace Lib\Files\Mappings;


/**
 * Класс проверки маппинга файловых таблиц
 *
 */
class FilesTableMapping
{
    private const NEEDED_INTERFACE = 'Tables\Files\Interfaces\FileTable';
    private const EXISTENT_INTERFACE = 'Tables\CommonInterfaces\Existent';
    protected ?int $errorCode = null;
    protected string $errorText;
    protected string $class;


    /**
     * Конструктор класса
     *
     * @param string $mappingLevel1 маппинг 1-го уровня константного массива FILE_TABLE_MAPPING
     * @param string $mappingLevel2 маппинг 2-го уровня константного массива FILE_TABLE_MAPPING
     */
    public function __construct(string $mappingLevel1, string $mappingLevel2)
    {
        // Проверка существования маппинга
        if (!isset(FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2])) {
            $this->errorCode = 1;
            $this->errorText = "Запрашиваемого маппинга mapping_level_1: '{$mappingLevel1}', mapping_level_2: '{$mappingLevel2}'  не существует";
            return;
        }

        // Получение названия класса таблицы файлов
        $class = FILE_TABLE_MAPPING[$mappingLevel1][$mappingLevel2];

        // Проверка на существование указанного в маппинге класса
        if (!class_exists($class)) {
            $this->errorCode = 2;
            $this->errorText = "Указанный в маппинге класс: '{$class}' не существует";
            return;
        }

        // Проверка на реализацию интерфейса
        if (
            !($interfaces = class_implements($class))
            || !in_array(self::NEEDED_INTERFACE, $interfaces, true)
            || !in_array(self::EXISTENT_INTERFACE, $interfaces, true)
        ) {
            $this->errorCode = 3;
            $this->errorText = "Указанный в маппинге класс: '{$class}' не реализует требуемый интерфейс: '" . self::NEEDED_INTERFACE . "' или '" . self::EXISTENT_INTERFACE . "'";
            return;
        }

        $this->class = $class;
    }


    /**
     * Предназначен для получения кода ошибки при проверке маппинга
     *
     * @return int|null <b>int</b> есть ошибки (код)<br>
     * <b>null</b> нет ошибок
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }


    /**
     * Предназначен для получения текста ошибки при проверке маппинга
     *
     * @return string текст ошибки
     */
    public function getErrorText(): string
    {
        return $this->errorText;
    }


    /**
     * Предназначен для получения названия класса из маппинга
     *
     * @return string имя класса
     */
    public function getClassName(): string
    {
        return $this->class;
    }
}