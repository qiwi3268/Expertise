<?php


namespace Lib\Signs\Mappings;


class SignsTableMapping extends \Lib\Files\Mappings\FilesTableMapping
{


    private string $neededInterface = 'Tables\Signs\Interfaces\SignTable';
    private string $fileClass;


    // *** Предполагается, что перед использованием данного класса маппинги были проверены классом FilesTableMapping,
    //     поэтому здесь считается, что они полностью корректны
    // Принимает параметры-----------------------------------
    // mappingLevel1 string : маппинг 1-го уровня константного массива FILE_TABLE_MAPPING
    // mappingLevel2 string : маппинг 2-го уровня константного массива FILE_TABLE_MAPPING
    //
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

        $interfaces = class_implements($signClass);

        // Проверка на реализацию интерфейса
        $neededInterface = $this->neededInterface;
        if (!$interfaces || !in_array($this->neededInterface, $interfaces, true)) {
            $this->errorCode = 3;
            $this->errorText = "Указанный в маппинге класс: '{$signClass}' не реализует требуемый интерфейс: '{$neededInterface}'";
            return;
        }

        $this->fileClass = $fileClass;
        $this->class = $signClass;
    }


    // Предназначен для получения названия класса из таблицы файлов
    // Возвращает параметры-----------------------------------
    // string : имя таблицы файлов
    //
    public function getFileClassName(): string
    {
        return $this->fileClass;
    }
}

