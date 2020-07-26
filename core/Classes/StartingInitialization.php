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
        
            if(mb_stripos($className, 'exception') !== false){                // Исключения
                require_once "{$this->rootPath}/Classes/Exceptions/{$className}.php";
            
            }elseif(mb_stripos($className,'table') !== false){                // Таблицы
                require_once "{$this->rootPath}/Classes/Tables/{$className}.php";
            
            }elseif(mb_stripos($className, 'actions') !== false){             // Действия
                require_once "{$this->rootPath}/Classes/Actions/{$className}.php";
            
            }elseif(mb_stripos($className, 'helper') !== false){              // Вспомогательные классы
                require_once "{$this->rootPath}/Classes/Helpers/{$className}.php";
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
