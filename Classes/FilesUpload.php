<?php


// Класс загрузки файлов из глобального массива $_FILES
//
class FilesUpload{

    // Массив файлов со всех input'ов
    private array $FILES = [];

    // Ассоциативный массив количества файлов в каждом из input
    private array $FILESCount = [];

    // Массив ошибок
    private array $errors = [];

    public function __construct(array $FILES){

        $this->FILES = $FILES;
        foreach ($FILES as $inputName => $files){
            $this->FILESCount[$inputName] = count(array_filter($files['name'], 'strlen'));
        }
    }


    // Предназначен для получения массива ошибок
    // Возвращает параметры-----------------------------------
    // array : ошибки
    //
    public function getErrors():array {
        return $this->errors;
    }


    // Предназначен для проверки наличия загруженных файлов
    // Возвращает параметры-----------------------------------
    // true  : есть файлы в одном из input'ов
    // false : файлы отсутствуют
    //
    public function checkFilesExist():bool {
        foreach($this->FILESCount as $count){
            if($count > 0) return true;
        }
        return false;
    }


    // Предназначен для получения массива имен файлов из определенного инпута
    // Принимает параметры------------------------------------
    // inputName  string : инпут, с которого будут браться файлы
    // Возвращает параметры-----------------------------------
    // array : имена файлов
    //
    public function getFilesName(string $inputName):array {
        return $this->FILES[$inputName]['name'];
    }
    
    
    // Предназначен для получения массива размеров файлов из определенного инпута
    // Принимает параметры------------------------------------
    // inputName  string : инпут, с которого будут браться файлы
    // Возвращает параметры-----------------------------------
    // array : размеры файлов (в байтах)
    //
    public function getFilesSize(string $inputName):array {
        return $this->FILES[$inputName]['size'];
    }


    // Предназначен для получения количества файлов из определенного инпута
    // Принимает параметры------------------------------------
    // inputName  string : инпут, с которого будут браться файлы
    // Возвращает параметры-----------------------------------
    // int : количество файлов
    //
    public function getFilesCount(string $inputName):int {
        return $this->FILESCount[$inputName];
    }


    // Предназначен для проверки файлов на предмет ошибки в момент загрузки на сервер
    // Возвращает параметры-----------------------------------
    // true  : нет ошибок
    // false : есть ошибки
    //
    public function checkServerUploadErrors():bool {

        $errors = [];

        // Массив файлов с одного input'а
        foreach($this->FILES as $inputName => $files){

            for($s = 0; $s < $this->FILESCount[$inputName]; $s++){

                if($files['error'][$s] != 0){

                    // Определение типа ошибки к файлу
                    switch($files['error'][$s]){
                        case 1:
                            $errorText = 'Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize';
                            break;
                        case 2:
                            $errorText = 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме';
                            break;
                        case 3:
                            $errorText = 'Загружаемый файл был получен только частично';
                            break;
                        case 4:
                            $errorText = 'Файл не был загружен';
                            break;
                        case 6:
                            $errorText = 'Отсутствует временная папка';
                            break;
                        case 7:
                            $errorText = 'Не удалось записать файл на диск';
                            break;
                        case 8:
                            $errorText = 'PHP-расширение остановило загрузку файла';
                            break;
                        default:
                            $errorText = 'Не найден код ошибки: ' .$files['error'][$s];
                            break;
                    }
                    $errors[] = ['name'  => $files['name'][$s],
                                 'error' => $errorText
                    ];
                }
            }
        }

        if(empty($errors)){
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    // Предназначен для проверки файлов на максимально допустимый размер
    // Принимает параметры------------------------------------
    // sizeMB int : максимально допустимый размер файла в Мб
    // Возвращает параметры-----------------------------------
    // true  : все файлы прошли проверки
    // false : есть файлы, превысившые размер
    //
    public function checkMaxFilesSize(int $sizeMB):bool {

        $errors = [];

        // Размер файла в байтах
        $sizeB = 1024 * 1024 * $sizeMB;

        // Массив файлов с одного input'а
        foreach($this->FILES as $inputName => $files){
            for($s = 0; $s < $this->FILESCount[$inputName]; $s++){

                if($files['size'][$s] > $sizeB){
                    $errors[] = $files['name'][$s];
                }
            }
        }

        if(empty($errors)){
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    // Предназначен для проверки файлов на допустимые форматы
    // Принимает параметры------------------------------------
    // formats  array : индексный массив форматов
    // isAllowed bool : true  - один из форматов обязательно должен присутствовать в файле
    //                  false - ни один из форматов не должен присутствовать в файле
    // Возвращает параметры-----------------------------------
    // true  : все файлы прошли проверки
    // false : есть файлы, которые не прошли проверки
    //
    public function checkFilesName(array $formats, bool $isAllowed):bool {

        $errors = [];

        // Флаг совпадения формата
        $formatFlag = false;

        // Массив файлов с одного input'а
        foreach($this->FILES as $inputName => $files){
            // Цикл по всей секции файлов
            for($s = 0; $s < $this->FILESCount[$inputName]; $s++){

                foreach($formats as $format){

                    if(mb_strpos($files['name'][$s], $format) !== false){

                        $formatFlag = true;
                        break;
                    }
                }

                // Если не было вхождения формата, а оно должно быть - ошибка
                // Если было вхождение формата, а его не должно быть - ошибка
                if((!$formatFlag && $isAllowed) || ($formatFlag && !$isAllowed)){
                    $errors[] = $files['name'][$s];
                }

                $formatFlag = false;
            }
        }

        if(empty($errors)){
            return true;
        }

        $this->errors = $errors;
        return false;
    }


    // Предназначен для загрузки файлов в указанну директорию
    // Принимает параметры------------------------------------
    // inputName  string : инпут, с которого будут браться файлы
    // dir        string : директория файлов для загрузки, должна оканчиваться на '/'
    // uploadNames array : массив с именами файлов, которые будут загружены в директорию
    //                     в случае необходимости загружать оригинальные имена файлов - ничего не передавать
    // Возвращает параметры-----------------------------------
    // true  : все файлы успешно загружены
    // false : произошли ошибки при загрузке файлов (вероятно, permission denied)
    //
    public function uploadFiles(string $inputName, string $dir, array $uploadNames = []):bool {

        $errors = [];

        if(!$uploadNames){
            $uploadNames = $this->getFilesName($inputName);
        }

        if(count($uploadNames) !== $this->FILESCount[$inputName]){
            throw new FileException('Размерность массива uploadNames не соответствует имеющемуся количеству файлов');
        }

        $files = $this->FILES[$inputName];

        for($s = 0; $s < $this->FILESCount[$inputName]; $s++){

            $uploadFile = $dir.basename($uploadNames[$s]);

            if(!move_uploaded_file($files['tmp_name'][$s], $uploadFile)){
                $errors[] = $files['name'][$s];
            }
        }

        if(empty($errors)){
            return true;
        }

        $this->errors = $errors;
        return false;
    }
}
