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
use Classes\Application\Miscs\Initialization\CreateFormInitializer;


$miscInitializer = new CreateFormInitializer();

$data = $miscInitializer->getPaginationSingleMiscs()['type_of_object'];


TemplateMaker::getInstance()->create(
    'type_of_object',
    TemplateMaker::HOME_WITH_DATA_CREATE_MISCS . '/type_of_object.php',
    $data
);

TemplateMaker::require('type_of_object');


/**
 * lalalala
 *
 */
final class TemplateMaker
{

    public const HOME_WITH_DATA_CREATE_MISCS = ROOT . '/views/home/templates_with_data/create/miscs';

    /**
     * Массих для хранения шаблонов
     *
     */
    private array $templates = [];

    /**
     * Сущность класса
     *
     */
    private static self $instance;


    /**
     * Конструктор класса
     *
     */
    private function __construct()
    {
    }


    /**
     * Предназначен для получения сущности класса
     *
     * @return static сущность класса
     */
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
     * @return mixed результат не статического метода
     * @throws SelfEx
     */
    static public function __callStatic(string $method, array $arguments)
    {
        $classMapping = [
            'require'     => 'requireByName',
            'getSelfData' => 'getDataByPath'
        ];

        if (isset($classMapping[$method])) {

            if ($method == 'getSelfData') {

                $arguments = [
                    debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['file']
                ];
            }

            return call_user_func_array([self::getInstance(), $classMapping[$method]], $arguments);
        } else {
            throw new SelfEx("Отсутствует callStatic маппинг для метода: '{$method}'", 1);
        }
    }


    /**
     * Предназначен для создания шаблона
     *
     * @param string $name уникальное название шаблона
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @param array|null $data
     * @throws SelfEx
     */
    public function create(string $name, string $path, ?array $data = null): void
    {
        if (isset($this->templates[$name])) {
            throw new SelfEx("Шаблон: '{$name}' уже существует", 2);
        }

        $this->checkPathExist($path);

        $this->templates[$name] = [
            'path' => $path,
            'data' => $data
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
        if (!isset($this->templates[$name])) {
            throw new SelfEx("Запрашиваемый шаблон: '{$name}' не существует", 4);
        }

        require $this->templates[$name]['path'];
    }


    /**
     * Предназначен для подключения файла шаблона без данных
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @throws SelfEx
     */
    public function requireTemplateNoDataByPath(string $path): void
    {
        $this->checkPathExist($path);
        require $path;
    }


    /**
     * Предназначен для получения данных для шаблона
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @return array|null
     * @throws SelfEx
     */
    public function getDataByPath(string $path): ?array
    {
        foreach ($this->templates as ['path' => $templatePath, 'data' => $data]) {

            if ($path == $templatePath) return $data;
        }
        throw new SelfEx("По пути: '{$path}' не найден шаблон для получения его данных", 777);
    }


    /**
     * Предназначен для проверки существования файла шаблона в ФС сервера
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @throws SelfEx
     */
    private function checkPathExist(string $path): void
    {
        if (!file_exists($path)) {
            throw new SelfEx("Файл шаблона по пути: '{$path}' не существует в файловой системе сервера", 3);
        }
    }
}