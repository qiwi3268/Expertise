<?php


namespace Lib\Singles;
use Lib\Exceptions\TemplateMaker as SelfEx;


/**
 * Предназначн для создания view шаблонов
 *
 */
final class TemplateMaker
{

    public const HOME_WITH_DATA_CREATE_MISCS = ROOT . '/views/home/templates_with_data/create/miscs';
    public const HOME_WITH_DATA_CREATE = ROOT . '/views/home/templates_with_data/create/';
    public const HOME_WITH_DATA_EDIT = ROOT . '/views/home/templates_with_data/edit/';
    public const HOME_WITH_DATA_VIEW = ROOT . '/views/home/templates_with_data/view/';

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
            throw new SelfEx("Указанный абсолютный футь в ФС сервера к шаблону: '{$name}' уже существует в другом шаблоне: '{$first_key}'", 2);
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
    static public function requireByName(string $name): void
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
     * @uses \Lib\Singles\TemplateMaker::getDataByPath()
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
            throw new SelfEx("Файл шаблона по пути: '{$path}' не существует в ФС сервера", 3);
        }
    }
}