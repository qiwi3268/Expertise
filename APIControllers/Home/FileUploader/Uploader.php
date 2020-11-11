<?php


namespace APIControllers\Home\FileUploader;

use Lib\Exceptions\File as SelfEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;

use core\Classes\Request\HttpRequest;
use Lib\Files\Uploader as UploaderToServer;
use Lib\DataBase\Transaction;


/**
 * Предназначен для загрузки файлов на сервер, включая проверки и создание соответствующих записей в БД
 *
 */
abstract class Uploader
{
    protected HttpRequest $request;
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
     * id главного документа к которому загружаются файлы
     *
     */
    protected int $mainDocumentId;

    /**
     * Полное наименование класса файловой таблицы
     *
     */
    protected string $fileTableClass;

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
     * @param string $directory абсолютный путь в ФС сервера до директории загрузки файлов
     * @param int $mainDocumentId id главного документа к которому загружаются файлы
     * @param string $fileTableClass полное наименование класса файловой таблицы
     */
    public function __construct(string $directory, int $mainDocumentId, string $fileTableClass)
    {
        $this->request = HttpRequest::getInstance();
        $this->uploader = new UploaderToServer($_FILES);
        $this->directory = $directory;
        $this->mainDocumentId = $mainDocumentId;
        $this->fileTableClass = $fileTableClass;
    }


    /**
     * Предназначен для подготовоительной проверки перед неподсредственной загрузкой файлов
     *
     * @throws SelfEx
     */
    public function preparatoryCheck(): void
    {
        $uploader = $this->uploader;

        if (!$uploader->checkFilesExist()) {
            throw new SelfEx('Отсутствуют загруженные файлы', 1001);
        }

        // Проверка на ошибки при загрузке файлов на сервер
        if (!$uploader->checkServerUploadErrors()) {

            $debug = [];

            foreach ($uploader->getErrors() as $error) {
                $debug[] = "file_name: '{$error['name']}', error_text: '{$error['error']}'";
            }

            $debug = implode(', ', $debug);
            throw new SelfEx("Произошли ошибки при загрузке файлов на сервер. {$debug}", 1002);
        }

        // Проверка на допустимые форматы файлов
        if (!$uploader->checkFilesName($this->allowedFormats, true)) {
            $debug = implode(', ', $uploader->getErrors());
            throw new SelfEx("Не пройдены проверки на допустимые форматы файлов. {$debug}", 1003);
        }

        // Проверка на запрещенные символы в файлах
        if (!$uploader->checkFilesName($this->forbiddenSymbols, false)) {

            $debug = implode(', ', $uploader->getErrors());
            throw new SelfEx("Не пройдены проверки на запрещенные символы. {$debug}", 1004);
        }

        // Проверка на максимальный размер файлов
        if (!$uploader->checkMaxFilesSize($this->maxFileSize)) {

            $debug = implode(', ', $uploader->getErrors());
            throw new SelfEx("Не пройдены проверки на максимально допустимый размер файлов. {$debug}", 1005);
        }
    }


    /**
     * Предназначен для загрузки файлов на сервер, включая создание записей в БД
     *
     * @return array индексный массив с ассоциативным массивом для каждого загруженного файла формата:<br>
     * 'id'        - id созданной записи для файла<br>
     * 'name'      - исходное имя файла<br>
     * 'hash'      - хэш для файла<br>
     * 'file_size' - размер файла в байтах
     * @throws SelfEx
     * @throws TransactionEx
     * @throws ReflectionException
     */
    public function upload(): array
    {
        $uploader = $this->uploader;
        $inputName = $this->inputName;
        $directory = $this->directory;

        $filesCount = $uploader->getFilesCount($inputName);

        // Генерация уникального хэша
        $hashes = [];
        $uniqueHashCount = 0;

        do {

            $hash = bin2hex(random_bytes(40)); // Длина 80 символов
            if (!file_exists("{$directory}/{$hash}")) {

                $hashes[] = $hash;
                $uniqueHashCount++;
            }
        } while ($uniqueHashCount != $filesCount);

        $filesName = $uploader->getFilesName($inputName);
        $filesSize = $uploader->getFilesSize($inputName);

        $createTransaction = $this->getCreateTransaction(
            $filesName,
            $filesSize,
            $hashes
        );

        try {
            $createdIds = $createTransaction->start()->getLastResults()[$this->fileTableClass]['create'];
        } catch (DataBaseEx $e) {
            throw new SelfEx(exceptionToString($e, 'Ошибка при создании записей в файловую таблицу'), 1006);
        }

        // Загрузка файлов в указанну директорию
        if (!$uploader->uploadFiles($inputName, "{$directory}/", $hashes)) {

            // Массив успешно загруженных файлов
            $successfullyUpload = array_diff($filesName, $uploader->getErrors());

            $errorArr = [];

            // Если часть файлов загрузилась - пробуем их удалить
            if (count($successfullyUpload) > 0) {

                foreach ($successfullyUpload as $file) {

                    // Имеются только имена успешно загруженных файлов. Находим их хэш
                    $fileIndex = array_search($file, $filesName, true);

                    $hash = $hashes[$fileIndex];

                    // Не получилось удалить файл
                    if ($fileIndex === false || !unlink("{$directory}/{$hash}")) {
                        $errorArr[] = $file;
                    }
                }
            }

            // Есть файлы, которые не получилось удалить
            if (!empty($errorArr)) {
                $debug = implode(', ', $errorArr);
                throw new SelfEx("Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных. {$debug}", 1007);
            } else {
                throw new SelfEx('Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные', 1008);
            }
        }

        // Транзакция обновления флагов загрузки файла на сервер в таблице
        $transaction = new Transaction();

        foreach ($createdIds as $id) $transaction->add($this->fileTableClass, 'setUploadedById', [$id]);

        try {
            $transaction->start();
        } catch (DataBaseEx $e) {
            throw new SelfEx(exceptionToString($e, 'Загрузка файлов прошла успешно, НО не получилось обновить флаги загрузки файла на сервер в таблице'), 1009);
        }

        // Формирование выходного результата
        $result = [];

        for ($l = 0; $l < $filesCount; $l++) {

            $result[] = [
                'id'        => $createdIds[$l],
                'name'      => $filesName[$l],
                'hash'      => $hashes[$l],
                'file_size' => $filesSize[$l]
            ];
        }
        return $result;
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
     * - inputName        (определено по умолчанию)
     * - allowedFormats   (определено по умолчанию)
     * - forbiddenSymbols (определено по умолчанию)
     * - maxFileSize      (определено по умолчанию)
     *
     */
    abstract function initializeProperties(): void;


    /**
     * Предназначен для получения транзации, в которой упакованы методы для создания записей в файловой таблице
     *
     * Все принимаемые параметры являются индексными массивами с одинаковой
     * и соответствующей друг другу очередностью индексов, начинающийся с 0
     *
     * @param array $filesName массив с названими файлов
     * @param array $filesSize массив с размерами файлов (в байт)
     * @param array $hashes массив уникальных хэшей
     * @return Transaction транзакция, в которой упаковано создание записей в файловой таблице
     */
    abstract protected function getCreateTransaction(array $filesName, array $filesSize, array $hashes): Transaction;
}