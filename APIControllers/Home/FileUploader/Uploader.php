<?php


namespace APIControllers\Home\FileUploader;
use Lib\Files\Uploader as UploaderToServer;



abstract class Uploader
{

    private UploaderToServer $uploader;

    /**
     * Наименование инпута, с которого будут браться файлы
     *
     */
    protected string $inputName = 'download_files';

    /**
     * Абсолютный путь в ФС сервера до директории загрузки файлов
     *
     * Начинается на '/'. Заканчивается без '/'
     *
     */
    private string $directory;

    /**
     * Массив расширений файлов, разрешенных к загрузке
     *
     */
    protected array $allowedFormats = ['.docx', '.doc', '.odt', '.pdf', '.xlsx', '.xls', '.ods', '.xml'];

    /**
     * Массив запрещенных символов в наименовании файла
     *
     */
    protected array $forbiddenSymbols = [','];

    /**
     * Максимально допустимый размер файла (в Мб)
     *
     */
    protected int $maxFileSize = 80;


    /**
     * Конструктор класса
     *
     * @param int $applicationId id заявления
     */
    public function __construct(int $applicationId)
    {
        $this->uploader = new UploaderToServer($_FILES);
        $this->directory = APPLICATIONS_FILES . "/{$applicationId}";
    }


    /**
     * Предназначен для получения массива обязательных параметров,
     * не включая mapping_level_1 и mapping_level_2
     *
     * В случае отсутствия иных параметров необходимо возвращать пустой массив
     *
     * @return array
     */
    abstract function getRequiredParams(): array;


    /**
     * Предназначен для инициализации свойств класса
     *
     * Необходимо инициализировать (переопределить) свойства:
     * - inputName (определено по умолчанию)
     * - allowedFormats (определено по умолчанию)
     * - forbiddenSymbols (определено по умолчанию)
     * - maxFileSize (определено по умолчанию)
     *
     */
    abstract function initializeProperties(): void;
}