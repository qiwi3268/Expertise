<?php


namespace Lib\Files\Mappings;


// Класс проверки маппинга файловых таблиц
//
class FilesTableMapping
{
    private const NEEDED_INTERFACE = 'Tables\Files\Interfaces\FileTable';
    protected ?int $errorCode = null;
    protected string $errorText;
    protected string $class;


    // Принимает параметры-----------------------------------
    // mappingLevel1 string : маппинг 1-го уровня константного массива FILE_TABLE_MAPPING
    // mappingLevel2 string : маппинг 2-го уровня константного массива FILE_TABLE_MAPPING
    //
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
        ) {
            $this->errorCode = 3;
            $this->errorText = "Указанный в маппинге класс: '{$class}' не реализует требуемый интерфейс: '" . self::NEEDED_INTERFACE . "'";
            return;
        }

        $this->class = $class;
    }


    // Предназначен для получения кода ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // null  : нет ошибок
    // int   : есть ошибки (код)
    //
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }


    // Предназначен для получения текста ошибки при проверке маппинга
    // Возвращает параметры-----------------------------------
    // string : текст ошибки
    //
    public function getErrorText(): string
    {
        return $this->errorText;
    }


    // Предназначен для получения названия класса из маппинга
    // Возвращает параметры-----------------------------------
    // string : имя класса
    //
    public function getClassName(): string
    {
        return $this->class;
    }
}