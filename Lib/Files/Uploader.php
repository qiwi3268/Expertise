<?php


namespace Lib\Files;

use Lib\Exceptions\File as SelfEx;

use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\Transaction as TransactionEx;
use ReflectionException;

use Lib\DataBase\Transaction;
use Lib\Files\Mappings\FilesTableMapping;


/**
 * Предназначен для загрузки файлов на сервер, включая проверки и создание соответствующих записей в БД
 *
 * Наследующий класс должен инициализировать свойства:
 * - filesTableMapping
 * - inputName
 * - directory
 * - allowedFormats
 * - forbiddenSymbols
 * - maxFileSize
 */
abstract class Uploader
{

    private UploaderToServer $uploader;

    protected FilesTableMapping $filesTableMapping;

    /**
     * Наименование инпута, с которого будут браться файлы
     *
     */
    protected string $inputName;

    /**
     * Абсолютный путь в ФС сервера до директории логов загрузки файлов
     *
     * Начинается на '/'. Заканчивается без '/'
     *
     */
    protected string $directory;

    /**
     * Массив расширений файлов, разрешенных к загрузке
     *
     */
    protected array $allowedFormats;

    /**
     * Массив запрещенных символов в наименовании файла
     *
     */
    protected array $forbiddenSymbols;

    /**
     * Максимально допустимый размер файла (в Мб)
     *
     */
    protected int $maxFileSize;


    /**
     * Конструктор класса
     *
     * @throws SelfEx
     */
    public function __construct()
    {
        $this->uploader = new UploaderToServer($_FILES);
        $this->preparatoryCheck();
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
            throw new SelfEx("Не пройдены проверки на запрещенные символы. {$debug}", 1005);
        }

        // Проверка маппинга файловых таблиц
        if (!is_null($this->filesTableMapping->getErrorCode())) {

            $debug = $this->filesTableMapping->getErrorText();
            throw new SelfEx("Ошибка маппинга файловой таблицы. {$debug}", 1006);
        }
    }


    /**
     * Предназначен для загрузки файлов на сервер, включая создание записей в БД
     *
     * @return array индексный массив с ассоциативным массивом для каждого загруженного файла формата:<br>
     * 'id' - id созданной записи для файла<br>
     * 'name' - исходное имя файла<br>
     * 'hash' - хэш для файла<br>
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

        // Генерация уникального хэша
        $filesCount = $uploader->getFilesCount($inputName);

        $hashes = [];
        $uniqueHashCount = 0;

        do {

            $hash = bin2hex(random_bytes(40)); // Длина 80 символов
            if (!file_exists("{$directory}/$hash")) {

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

        $class = $this->filesTableMapping->getClassName();

        try {

            $createdIds = $createTransaction->start()->getLastResults()[$class]['create'];
        } catch (DataBaseEx $e) {

            throw new SelfEx(exceptionToString($e, 'Ошибка при создании записи в файловую таблицу'), 1007);
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
                throw new SelfEx("Возникли ошибки при переносе загруженного файла в указанную директорию, и НЕ получилось удалить часть из успешно загруженных. {$debug}", 1008);
            } else {

                throw new SelfEx('Возникли ошибки при переносе загруженного файла в указанную директорию, НО получилось удалить (или их не было) успешно загруженные', 1009);
            }
        }

        // Транзакция обновления флагов загрузки файла на сервер в таблице
        $transaction = new Transaction();

        foreach ($createdIds as $id) $transaction->add($class, 'setUploadedById', [$id]);

        try {

            $transaction->start();
        } catch (DataBaseEx $e) {

            throw new SelfEx(exceptionToString($e, 'Загрузка файлов прошла успешно, НО не получилось обновить флаги загрузки файла на сервер в таблице'), 10010);
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
     * Предназначен для получения транзации, в которой упакованы методы для создания записей в файловой таблице
     *
     * Все принимаемые параметры являются индексными массивами с одинаковой
     * и соответствующей друг другу очередностью индексов, начинающийся с 0
     *
     * @param array $filesName индексный массив с названими файлов
     * @param array $filesSize индексный массив с размерами файлов (в байт)
     * @param array $hashes индексный массив уникальных хэшей
     * @return Transaction транзакция, в которой упаковано создание записей в файловой таблице
     */
    abstract protected function getCreateTransaction(array $filesName, array $filesSize, array $hashes): Transaction;
}