<?php

use core\Classes\Session;
use Exception as SelfEx;
use Lib\Responsible\Responsible;
use Lib\Singles\PrimitiveValidator;
use Tables\user;
use Tables\people_name;
use Tables\Docs\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;
use Tables\Actions\application as ApplicationActions;
use core\Classes\RoutesXMLHandler;
use Tables\Structures\documentation_1;
use Lib\Singles\Helpers\PageAddress;
use Tables\assigned_expert_total_cc;
use Classes\Application\FinancingSources;
use Lib\DataBase\Transaction;
use Tables\test;

TemplateMaker::getData();


final class TemplateMaker
{

    private array $templates = [];

    private static self $instance;


    private function __construct()
    {
    }

    static public function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Магический метод для перенаправления обращений (согласно маппингу) к не статическим методам
     *
     * @param string $method
     * @param array $arguments
     * @throws SelfEx
     */
    static public function __callStatic(string $method, array $arguments): void
    {
        $classMapping = [
            'create'  => 'create',
            'require' => 'requireByName',
            'getData' => 'getDataByPath'
        ];

        if (isset($classMapping[$method])) {
            call_user_func_array([self::getInstance(), $classMapping[$method]], $arguments);
        } else {
            throw new SelfEx("Отсутствует callStatic маппинг для метода: '{$method}'", 1);
        }
    }


    /**
     * Предназначен для создания шаблона
     *
     * @param string $name уникальное название шаблона
     * @param string $dir абсолютный путь в ФС сервера к директории с шаблоном.<br>
     * Начинается на '/', заканчивается <b>без</b> '/'
     * @param string $fileName
     * @param array|null $data
     * @throws SelfEx
     */
    public function create(string $name, string $dir, string $fileName, ?array $data = null): void
    {
        if (isset($this->templates[$name])) {
            throw new SelfEx("Шаблон: '{$name}' уже существует", 2);
        }

        $path = "{$dir}/{$fileName}";

        if (!file_exists($path)) {
            throw new SelfEx("Файл шаблона: '{$name}' по пути: '{$path}' не существует", 3);
        }

        $this->templates[$name] = [
            'dir'      => $dir,
            'fileName' => $fileName,
            'data'     => $data
        ];
    }


    /**
     * Предназначен для включения файла шаблона по его названию
     *
     * @param string $name уникальное название шаблона
     * @throws SelfEx
     */
    public function requireByName(string $name): void
    {
        if (isset($this->templates[$name])) {
            throw new SelfEx("Запрашиваемый шаблон: '{$name}' не существует", 4);
        }

        list('dir' => $dir, 'fileName' => $fileName) = $this->templates[$name];

        require "{$dir}/{$fileName}";
    }


    /**
     * Предназначен для получения данных для шаблона
     *
     * @param string|null $path <b>string</b> абсолютный путь в ФС сервера к файлу шаблона.<br>
     * Начинается на '/', заканчивается <b>без</b> '/', включая расширение файла<br>
     * <b>null</b> в случае, если шаблон будет браться по пути к файлу,
     * в котором вызывается данный метод
     * @return array|null
     * @throws SelfEx
     */
    public function getDataByPath(?string $path = null): ?array
    {
        if (is_null($path)) {
            $path = __FILE__;
        }

        foreach ($this->templates as ['dir' => $dir, 'fileName' => $fileName, 'data' => $data]) {

            $templatePath = "{$dir}/{$fileName}";

            if ($path == $templatePath) {
                return $data;
            }
        }
        throw new SelfEx("По пути: '{$path}' не найден шаблон для получения его данных", 777);
    }
}