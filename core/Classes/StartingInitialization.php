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
    public function enableClassAutoloading():void {
    
        // Автозагрузка классов
        spl_autoload_register(function(string $className){
    
            $path = null;
            
            $isNamespace = contains($className, '\\');
            
            // Автозагрузка для классом с пространством имён
            if($isNamespace){
                
                $namespacePath = str_replace('\\', '/', $className);
                $pattern = "/\A(.+)\/(.+\z)/";
                list(1 => $tmp_path, 2 => $tmp_name) =  GetHandlePregMatch($pattern, $namespacePath, false);
                $path = "{$this->rootPath}/{$tmp_path}/{$tmp_name}.php";
            
            // Автозагрузка предопределенных файлов
            }else{
                
                // Исключения
                if(icontains($className,'exception')) $path = "{$this->rootPath}/Classes/Exceptions/{$className}.php";
                // Файловые таблицы
                elseif(icontains($className,'file', 'table')) $path = "{$this->rootPath}/Classes/Tables/File/{$className}.php";
                // Таблицы подписей
                elseif(icontains($className,'sign', 'table')) $path = "{$this->rootPath}/Classes/Tables/Sign/{$className}.php";
                // Таблицы
                elseif(icontains($className,'table')) $path = "{$this->rootPath}/Classes/Tables/{$className}.php";
                // Действия
                elseif(icontains($className,'actions')) $path = "{$this->rootPath}/Classes/Actions/{$className}.php";
                // Вспомогательные классы
                elseif(icontains($className,'helper')) $path = "{$this->rootPath}/Classes/Helpers/{$className}.php";
            }
            
            
            
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
        require_once "{$this->rootPath}/core/Classes/Cookie.php";
        require_once "{$this->rootPath}/core/Classes/Access.php"; //todo
        require_once "{$this->rootPath}/core/Classes/Route.php";
    }
}
