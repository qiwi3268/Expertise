<?php


// Предназначен для стартовой инициализации программы в виде подключения/объявления необходимых компонентов
//
class StartingInitialization{
    
    // Коревая директория проекта
    private string $rootPath;
    
    function __construct(string $rootPath){
        
        $this->rootPath = $rootPath;
    }
    
    // Предназначен для включения автозагрузки классов
    //
    public function enableClassAutoloading(){
    
        // Автозагрузка классов
        spl_autoload_register(function(string $className){
            
            $path = null;
    
            // Исключения
            if(mb_stripos($className, 'exception') !== false) $path = "{$this->rootPath}/Classes/Exceptions/{$className}.php";
            // Таблицы
            elseif(mb_stripos($className,'table') !== false) $path = "{$this->rootPath}/Classes/Tables/{$className}.php";
            // Действия
            elseif(mb_stripos($className, 'actions') !== false) $path = "{$this->rootPath}/Classes/Actions/{$className}.php";
            // Вспомогательные классы
            elseif(mb_stripos($className, 'helper') !== false) $path = "{$this->rootPath}/Classes/Helpers/{$className}.php";
            
            if(!is_null($path) && file_exists($path)){
                require_once $path;
            }
        });
    }
    
    // Предназначен для подключения определенных констант
    public function requireDefinedVariables():void {
        require_once "{$this->rootPath}/core/defined_variables.php";
    }
    
    // Предназначен для подключения пакета базы данных
    public function requireDataBasePack():void {
        require_once "{$this->rootPath}/core/Classes/DataBase.php";
        require_once "{$this->rootPath}/core/Classes/ParametrizedQuery.php";
        require_once "{$this->rootPath}/core/Classes/SimpleQuery.php";
    
        require_once "{$this->rootPath}/Classes/Exceptions/Trait_exception.php";
        require_once "{$this->rootPath}/Classes/Exceptions/DataBaseException.php";
        require_once "{$this->rootPath}/Classes/Exceptions/TableException.php";
    }
    
    //todo после разработки системы callback'ов - перебрать данный метод
    public function requireWebPack():void {
        require_once "{$this->rootPath}/core/Classes/Session.php";
        require_once "{$this->rootPath}/core/Classes/Access.php";
        require_once "{$this->rootPath}/core/Classes/Route.php";
    }
}
