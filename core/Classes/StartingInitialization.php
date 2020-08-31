<?php


namespace core\Classes;


// Предназначен для стартовой инициализации программы в виде подключения/объявления необходимых компонентов
//
class StartingInitialization
{

    // Коревая директория проекта
    private string $rootPath;

    function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    // Предназначен для включения автозагрузки классов
    //
    public function enableClassAutoLoading(): void
    {
        // Автозагрузка классов
        spl_autoload_register(function (string $className) {


            $namespacePath = str_replace('\\', '/', $className);
            $pattern = "/\A(.+)\/(.+\z)/";
            list(1 => $tmp_path, 2 => $tmp_name) = GetHandlePregMatch($pattern, $namespacePath, false);
            $path = "{$this->rootPath}/{$tmp_path}/{$tmp_name}.php";


            if (file_exists($path)) {
                require_once $path;
            }
        });
    }
}
