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
 * Предназначн для
 *
 */
final class TemplateMaker
{

    public const HOME_WITH_DATA_CREATE_MISCS = ROOT . '/views/home/templates_with_data/create/miscs';

    /**
     * Массих для хранения шаблонов
     *
     */
    static private array $templates = [];


    /**
     * Предназначен для регистрации (создания) шаблона
     *
     * @param string $name уникальное название шаблона
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @param array|null $data данные для шаблона
     * @throws SelfEx
     */
    static public function registration(string $name, string $path, ?array $data = null): void
    {
        if (isset(self::$templates[$name])) {
            throw new SelfEx("Шаблон: '{$name}' уже существует", 1);
        }

        list(
            'count'     => $count,
            'first_key' => $first_key
            ) = arrayEntry(self::$templates, 'path', $path);

        if ($count > 0) {
            throw new SelfEx("Указанный абсолютный футь в ФС сервера к шаблону: '{$name}' уже существует в шаблоне: '{$first_key}'", 2);
        }

        self::checkPathExist($path);

        self::$templates[$name] = [
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
        if (!isset(self::$templates[$name])) {
            throw new SelfEx("Запрашиваемый шаблон: '{$name}' не существует", 4);
        }
        require self::$templates[$name]['path'];
    }


    /**
     * Предназначен для подключения файла шаблона без данных
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @throws SelfEx
     */
    static public function requireTemplateNoDataByPath(string $path): void
    {
        self::checkPathExist($path);
        require $path;
    }


    /**
     * Получает данных для шаблона, в котором вызывается этот метод
     *
     * @uses TemplateMaker::getDataByPath() //todo
     * @return array|null
     * @throws SelfEx
     */
    static public function getSelfData(): ?array
    {
        $path = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['file'];
        return self::getDataByPath($path);
    }


    /**
     * Предназначен для получения данных для шаблона
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @return array|null
     * @throws SelfEx
     */
    static public function getDataByPath(string $path): ?array
    {
        foreach (self::$templates as ['path' => $templatePath, 'data' => $data]) {

            if ($path == $templatePath) return $data;
        }
        throw new SelfEx("По пути: '{$path}' не найден шаблон для получения данных", 5);
    }


    /**
     * Предназначен для проверки существования файла шаблона в ФС сервера
     *
     * @param string $path абсолютный путь в ФС сервера к файлу шаблона
     * @throws SelfEx
     */
    static private function checkPathExist(string $path): void
    {
        if (!file_exists($path)) {
            throw new SelfEx("Файл шаблона по пути: '{$path}' не существует в файловой системе сервера", 3);
        }
    }
}