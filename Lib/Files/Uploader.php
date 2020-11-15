<?php


namespace Lib\Files;

use Lib\Exceptions\File as SelfEx;


/**
 * Класс загрузки файлов из глобального массива $_FILES на сервер
 *
 */
class Uploader
{

    /**
     * Массив файлов со всех input'ов
     *
     */
    private array $FILES;

    /**
     * Ассоциативный массив количества файлов в каждом из input
     *
     */
    private array $FILESCount = [];

    /**
     * Массив ошибок
     *
     */
    private array $errors = [];


    /**
     * Констркутор класса
     *
     * @param array $FILES массив файлов. Обычно - суперглобальный массив $_FILES
     */
    public function __construct(array $FILES)
    {
        $this->FILES = $FILES;
        foreach ($FILES as $inputName => $files) {
            $this->FILESCount[$inputName] = count(array_filter($files['name'], 'strlen'));
        }
    }


    /**
     * Предназначен для получения массива ошибок
     *
     * @return array массив ошибок
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * Предназначен для проверки наличия загруженных файлов
     *
     * @return bool <b>true</b> есть файлы в одном из input'ов<br><b>false</b> файлы отсутствуют
     */
    public function checkFilesExist(): bool
    {
        foreach ($this->FILESCount as $count) {
            if ($count > 0) return true;
        }
        return false;
    }


    /**
     * Предназначен для получения массива имен файлов из определенного инпута
     *
     * @param string $inputName инпут, с которого будут браться файлы
     * @return array массив имен файлов
     */
    public function getFilesName(string $inputName): array
    {
        return $this->FILES[$inputName]['name'];
    }



    /**
     * Предназначен для получения массива размеров файлов из определенного инпута
     *
     * @param string $inputName инпут, с которого будут браться файлы
     * @return array массив размеров файлов <i>(в байтах)</i>
     */
    public function getFilesSize(string $inputName): array
    {
        return $this->FILES[$inputName]['size'];
    }


    /**
     * Предназначен для получения количества файлов из определенного инпута
     *
     * @param string $inputName инпут, с которого будут браться файлы
     * @return int количество файлов
     */
    public function getFilesCount(string $inputName): int
    {
        return $this->FILESCount[$inputName];
    }


    /**
     * Предназначен для проверки файлов на предмет ошибки в момент загрузки на сервер
     *
     * @return bool <b>true</b> нет ошибок<br>
     * <b>false</b> есть ошибки
     */
    public function checkServerUploadErrors(): bool
    {
        $errors = [];

        // Массив файлов с одного input'а
        foreach ($this->FILES as $inputName => $files) {

            for ($l = 0; $l < $this->FILESCount[$inputName]; $l++) {

                if ($files['error'][$l] != 0) {

                    // Определение типа ошибки к файлу
                    switch ($files['error'][$l]) {
                        case 1 :
                            $errorText = 'Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize';
                            break;
                        case 2 :
                            $errorText = 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме';
                            break;
                        case 3 :
                            $errorText = 'Загружаемый файл был получен только частично';
                            break;
                        case 4 :
                            $errorText = 'Файл не был загружен';
                            break;
                        case 6 :
                            $errorText = 'Отсутствует временная папка';
                            break;
                        case 7 :
                            $errorText = 'Не удалось записать файл на диск';
                            break;
                        case 8 :
                            $errorText = 'PHP-расширение остановило загрузку файла';
                            break;
                        default :
                            $errorText = 'Не найден код ошибки: ' . $files['error'][$l];
                            break;
                    }
                    $errors[] = [
                        'name'  => $files['name'][$l],
                        'error' => $errorText
                    ];
                }
            }
        }

        if (empty($errors)) {
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    /**
     * Предназначен для проверки файлов на максимально допустимый размер
     *
     * @param int $sizeMB максимально допустимый размер файла в Мб
     * @return bool <b>true</b> все файлы прошли проверки<br>
     * <b>false</b> есть файлы, превысившие размер
     */
    public function checkMaxFilesSize(int $sizeMB): bool
    {
        $errors = [];

        // Размер файла в байтах
        $sizeB = 1024 * 1024 * $sizeMB;

        // Массив файлов с одного input'а
        foreach ($this->FILES as $inputName => $files) {

            for ($l = 0; $l < $this->FILESCount[$inputName]; $l++) {

                if ($files['size'][$l] > $sizeB) {
                    $errors[] = $files['name'][$l];
                }
            }
        }

        if (empty($errors)) {
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    /**
     * Предназначен для проверки файлов на допустимые форматы
     *
     * @param array $formats индексный массив форматов
     * @param bool $isAllowed <b>true</b> один из форматов обязательно должен присутствовать в файле<br>
     * <b>false</b> ни один из форматов не должен присутствовать в файле
     * @return bool <b>true</b> все файлы прошли проверки<br>
     * <b>false</b> есть файлы, которые не прошли проверки
     */
    public function checkFilesName(array $formats, bool $isAllowed): bool
    {
        $errors = [];

        // Флаг совпадения формата
        $formatFlag = false;

        // Массив файлов с одного input'а
        foreach ($this->FILES as $inputName => $files) {
            // Цикл по всей секции файлов
            for ($l = 0; $l < $this->FILESCount[$inputName]; $l++) {

                foreach ($formats as $format) {

                    if (contains($files['name'][$l], $format)) {

                        $formatFlag = true;
                        break;
                    }
                }

                // Если не было вхождения формата, а оно должно быть - ошибка
                // Если было вхождение формата, а его не должно быть - ошибка
                if ((!$formatFlag && $isAllowed) || ($formatFlag && !$isAllowed)) {
                    $errors[] = $files['name'][$l];
                }
                $formatFlag = false;
            }
        }

        if (empty($errors)) {
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    /**
     * Предназначен для загрузки файлов в указанну директорию
     *
     * @param string $inputName инпут, с которого будут браться файлы
     * @param string $dir директория файлов для загрузки, <b>должна оканчиваться на '/'</b>
     * @param array $uploadNames массив с именами файлов, которые будут загружены в директорию<br>
     * в случае необходимости загружать оригинальные имена файлов - ничего не передавать
     * @return bool <b>true</b> все файлы успешно загружены<br>
     * <b>false</b> произошли ошибки при загрузке файлов <i>(вероятно, permission denied)</i>
     * @throws SelfEx
     */
    public function uploadFiles(string $inputName, string $dir, array $uploadNames = []): bool
    {
        $errors = [];

        if (empty($uploadNames)) {
            $uploadNames = $this->getFilesName($inputName);
        }

        if (count($uploadNames) != $this->FILESCount[$inputName]) {
            throw new SelfEx('Размерность массива uploadNames не соответствует имеющемуся количеству файлов');
        }

        $files = $this->FILES[$inputName];

        for ($l = 0; $l < $this->FILESCount[$inputName]; $l++) {

            $uploadFile = $dir . basename($uploadNames[$l]);

            if (!move_uploaded_file($files['tmp_name'][$l], $uploadFile)) {
                $errors[] = $files['name'][$l];
            }
        }

        if (empty($errors)) {
            return true;
        }

        $this->errors = $errors;
        return false;
    }
}
