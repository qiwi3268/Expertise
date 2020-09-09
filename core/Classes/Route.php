<?php


namespace core\Classes;

use core\Classes\Exceptions\Route as SelfEx;
use core\Classes\Session;
use Lib\Actions\Locator as ActionLocator;
use Tables\Exceptions\Exception;

final class Route
{
    private RouteCallback $routeCallback;

    // Полный запрос с первым '/' и get-параметрами
    private string $URI;
    // Запрос в формате без первого '/' и get-параметров
    private string $URN;
    // Директория, в которой должны находится файлы роута
    private string $dir;

    // Роут текущего запроса
    private array $route;
    private bool $routeExist = false;


    public function __construct(string $requestURI)
    {
        $this->URI = $requestURI;

        $this->URN = mb_substr(parse_url($requestURI, PHP_URL_PATH), 1);

        $this->dir = mb_substr($this->URN, 0, mb_strripos($this->URN, '/'));

        // Массив всех роутов
        $allRoutes = require_once(ROOT . '/core/routes.php');

        if (isset($allRoutes[$this->URN])) {

            $this->route = $allRoutes[$this->URN];
            $this->routeExist = true;
        }

        $this->routeCallback = new RouteCallback($this);
    }


    // Метод для проверки существования запрашиваемого роута
    // Возвращает параметры-----------------------------------
    // true  - роут существует
    // false - роут не существует
    //
    public function checkRoute(): bool
    {
        return $this->routeExist;
    }


    // Метод для получения URN текущего запроса
    // Возвращает параметры-----------------------------------
    // string : URN текущего запроса
    //
    public function getURN(): string
    {
        return $this->URN;
    }


    // Метод для проверки роута на корректность
    // + ни в одном из элементов не должно быть символа пробела
    //
    // + redirect - строка
    //
    // + access - массив - внутри только строки
    //
    // + все остальные routeUnit - массивы
    //
    // + если routeUnit содержит ABS, то все unit должны начинаться со '/' и
    //	 иметь одно из допустимых расширений файла
    //
    // + если массив индексный (общего доступа) - внутри только строки
    //
    // + если массив НЕ индексный (ограниченного доступа) - внутри только массивы,
    //	 в которых только строки
    //
    //todo проверку на абсолютную директорию (новый функционал)
    public function checkRouteCorrect()
    {
        $tmpArr = $this->route;

        $userCallbacks = array_filter(
            $tmpArr,
            fn($routeUnit) => (containsAll($routeUnit, 'user_callback')),
            ARRAY_FILTER_USE_BOTH
        );



        // Отдельная проверка для redirect
        if (isset($tmpArr['redirect'])) {

            if (!is_string($tmpArr['redirect'])) {
                throw new SelfEx('redirect должен быть ключом строки');
            }
            if (containsAll($tmpArr['redirect'], ' ')) {
                throw new SelfEx('redirect сореждит пробелы');
            }
            unset($tmpArr['redirect']);
        }

        // Отдельная проверка для access
        if (isset($tmpArr['access'])) {

            if (!is_array($tmpArr['access'])) {
                throw new SelfEx('access должен быть ключом массива');
            }
            foreach ($tmpArr['access'] as $function) {
                if (!is_string($function) || containsAll($function, ' ')) {
                    throw new SelfEx('access function не является строкой или содержит пробелы');
                }
            }
            unset($tmpArr['access']);
        }

        // Отдельная проверка для check_action
        if (isset($tmpArr['check_action'])) {

            if (!is_bool($tmpArr['check_action'])) {
                throw new SelfEx('check_action должен быть boolean значением');
            }
            unset($tmpArr['check_action']);
        }


        // Проверка всего остального
        foreach ($tmpArr as $routeUnit => $unitList) {

            if (containsAll($routeUnit, ' ')) {
                throw new SelfEx("routeUnit '{$routeUnit}' содержит пробелы");
            }
            if (!is_array($unitList)) {
                throw new SelfEx("routeUnit '{$routeUnit}' должен быть ключом массива");
            }

            // Флаг ABS
            $ABSflag = containsAll($routeUnit, 'ABS') ? true : false;

            foreach ($unitList as $contentFunction => $unit) {

                // Юнит с общим доступом
                if (is_numeric($contentFunction)) {

                    if (!is_string($unit) || containsAll($unit, ' ')) {
                        throw new SelfEx("unit в '{$routeUnit}' не является строкой или содержит пробелы");
                    }
                    if ($ABSflag && !$this->checkABSfile($unit)) {
                        throw new SelfEx("unit '{$unit}' в '{$routeUnit}' должен являться абсолютным путем к файлу");
                    }

                    // Юнит с ограниченным доступом
                } else {

                    if (containsAll($contentFunction, ' ')) {
                        throw new SelfEx("contentFunction '{$contentFunction}' в '{$routeUnit}' содержит пробелы");
                    }
                    if (!is_array($unit)) {
                        throw new SelfEx("contentFunction '{$contentFunction}' в '{$routeUnit}' должен быть ключом массива");
                    }

                    foreach ($unit as $accessUnitKey => $accessUnit) {

                        if (!is_numeric($accessUnitKey)) {
                            throw new SelfEx("accessUnit '{$accessUnit}' в '{$routeUnit}' должен быть элементом индексного массива");
                        }
                        if (!is_string($accessUnit) || containsAll($accessUnit, ' ')) {
                            throw new SelfEx("В contentFunction '{$contentFunction}' в '{$routeUnit}' один из accessUnit не является строкой или содержит пробелы");
                        }
                        if ($ABSflag && !$this->checkABSfile($accessUnit)) {
                            throw new SelfEx("accessUnit '{$accessUnit}' в contentFunction '{$contentFunction}' в '{$routeUnit}'  должен являться абсолютным путем к файлу");
                        }
                    }
                }
            }
        }
    }


    // Метод для проверки названия файла на соответствие абсолютному пути
    // название должно: начинаться с '/' и иметь одно из расширений
    // Возвращает параметры-----------------------------------
    // true  - название файла соответствует абсолютному пути
    // false - название файла не соответствует абсолютному пути
    //
    private function checkABSfile(string $fileName): bool
    {
        if ($fileName[0] == '/') return containsAny($fileName, '.php', '.html', '.css', '.js');

        return false;
    }


    // Метод возвращает редирект из роута, или false - если его нет
    // Возвращает параметры-----------------------------------
    // string : страница для редиректа
    // false - у роута отсутствует редирект
    //
    public function getRedirect()
    {
        return $this->route['redirect'] ?? false;
    }


    // Метод проверяет доступ пользователя к странице, вызывая access функции
    //
    public function checkAccess(): void
    {
        if (isset($this->route['access'])) {

            foreach ($this->route['access'] as $function) {

                if (method_exists('Access', $function)) {
                    Access::$function();
                } else {
                    throw new SelfEx("В классе Access отсутствует метод $function()");
                }
            }
        }
    }


    // Предназначен для проверки доступа к дейстувию
    //
    public function checkAction(): void
    {
        if (isset($this->route['check_action'])) {

            $actions = ActionLocator::getInstance()->getActions();

            $accessActions = $actions->getAccessActions();
            $executionActions = $actions->getExecutionActions();

            if (!$accessActions->checkAccessFromActionByPageName($this->URN)) {
                Session::setErrorMessage("Действие по странице: '{$this->URN}' недоступно");
                header('Location: /home/navigation');
            }

            $executionActions->checkIssetCallbackByPageName($this->URN);
        }
    }


    // Метод для получения подключаемых к странице файлов
    // Возвращает параметры-----------------------------------
    // array : подключаемые файлы
    //
    public function getRequiredFiles(): array
    {
        $tmpArr = $this->route;

        // Удаление из роута redirect и access, т.к. работа с ними
        // должна быть уже произведена
        unset($tmpArr['redirect'], $tmpArr['access'], $tmpArr['check_action']);

        // Удаление пустых routeUnit
        $tmpArr = array_filter($tmpArr, fn($value) => !empty($value));

        // 1 - составление роута под пользователя согласно доступа к контенту
        $routeForUser = [];
        $tmpUnitList = [];
        foreach ($tmpArr as $routeUnit => $unitList) {

            foreach ($unitList as $contentFunction => $unit) {

                // Юнит с общим доступом
                if (is_numeric($contentFunction)) {
                    $tmpUnitList[] = $unit;
                    continue;
                }

                // Юнит с ограниченным доступом
                // ..проверка наличия метода в классе проверки доступа к контенту Иначе exception
                // Вызов метода
                // Если True, то $tmpUnitList[] = ...
            }

            if (!empty($tmpUnitList)) {
                $routeForUser[$routeUnit] = $tmpUnitList;
                unset($tmpUnitList);
            }
        }

        // 2 - сбор подключаемых файлов из роута пользователя
        $requiredFiles = [];

        foreach ($routeForUser as $routeUnit => $unitList) {

            // Выбор типа функции рассчета пути в зависимости от routeUnit
            if (containsAll($routeUnit, 'ABS')) {    // Абсолютный файл

                // Расчет пути к файлу
                //	property : индексный массив
                //	[0] - fileName
                //	[1] - fileFolder
                $calcFilePath = function (array $property): string {
                    return ROOT . $property[0];
                };

            } elseif ($routeUnit[0] == '/') {                         // Абсолютная директория

                $tmpStrPos = mb_strpos($routeUnit, '%');

                if ($tmpStrPos != false) {
                    $routeUnit = mb_substr($routeUnit, 0, $tmpStrPos);
                }

                $calcFilePath = function (array $property): string {
                    return ROOT . "{$property[1]}{$property[0]}.php";
                };

            } elseif (mb_strpos($routeUnit, 'ROOT') !== false) {

                $calcFilePath = function (array $property): string {
                    $fileFolder = str_replace('ROOT', '', $property[1]);
                    return ROOT . "/{$fileFolder}/{$property[0]}.php";
                };
            } else {

                $calcFilePath = function (array $property): string {
                    $separator = empty($this->dir) ? '' : '/';
                    return ROOT . "/{$property[1]}{$separator}{$this->dir}/{$property[0]}.php";
                };
            }

            // Сбор подключаемых файлов
            foreach ($unitList as $unit) {

                $requiredFiles[] = [
                    'type' => $routeUnit,
                    'path' => $calcFilePath([$unit, $routeUnit])
                ];
            }
        }

        return $requiredFiles;
    }
}