<?php


namespace Lib\ViewModes;

use Lib\Exceptions\ViewModes as SelfEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use functions\Exceptions\Functions as FunctionsEx;
use ReflectionException;

use core\Classes\Session;


/**
 * Предназначен для работы с режимами просмотра карточек экспертизы
 *
 * Паттерн: <i>Singleton</i>
 */
class ViewModes
{

    /**
     * Сущность класса
     *
     */
    static private self $instance;

    /**
     * Пространство имен классов проверки доступа к режимам просмотра
     *
     */
    private const NAMESPACE = '\Classes\ViewModes';

    /**
     * Тип документа
     *
     */
    private string $documentType;

    /**
     * Экземпляр класса проверки доступа к режимам просмотра
     *
     */
    private $object;

    /**
     * Индексный массив с ассоциативными массивами режимов просмотра
     *
     */
    private array $modes;


    /**
     * Конструктор класса
     *
     * @param string $documentType тип документа
     * @throws SelfEx
     * @throws XMLValidatorEx
     * @throws ReflectionException
     */
    private function __construct(string $documentType)
    {
        $handler = new ViewModesXMLHandler();

        $document = $handler->getDocument($documentType);

        $handler->validateDocumentStructure($document);

        list(
            'class' => $class,
            'modes' => $modes
            ) = $handler->getHandledModesValue($document, self::NAMESPACE);

        foreach ($modes as &$mode) {
            $mode['result'] = null;
        }
        unset($mode);

        $this->documentType = $documentType;
        $this->object = new $class();
        $this->modes = $modes;
    }


    /**
     * Предназначен для получения сущности класса
     *
     * @param string|null $documentType <b>string</b> тип документа, если класс инициализируется впервые<br>
     * <b>null</b> как параметр по умолчанию
     * @return static сущность класса
     * @throws SelfEx
     * @throws XMLValidatorEx
     * @throws ReflectionException
     */
    static public function getInstance(?string $documentType = null): self
    {
        if (empty(self::$instance)) {

            if (is_null($documentType)) {
                throw new SelfEx("Необходимо передать параметр 'documentType' для первой инициализации класса", 2001);
            }
            self::$instance = new self($documentType);
        } elseif (!is_null($documentType)) {

            throw new SelfEx("Параметр 'documentType' требуется передавать только для первой инициализации класса", 2002);
        }
        return self::$instance;
    }


    /**
     * Предназначен для проверки доступа к режиму просмотра по URN страницы
     *
     * @param string $URN URN страницы
     * @return bool результат вызова метода проверки доступа
     * @throws SelfEx
     */
    public function checkAccessToViewModeByURN(string $URN): bool
    {
        // начало текста
        // home/expertise_cards/
        // 1 группа
        //    любой не пробельный символ один и более раз
        // /
        // 2 группа:
        //    любой не пробельный символ один и более раз
        // конец текста
        // - регистронезависимые
        // - использование кодировки utf-8
        $pattern = "/\Ahome\/expertise_cards\/(\S+)\/(\S+)\z/iu";

        try {
            list(
                1 => $documentType,
                2 => $modeName) = getHandlePregMatch($pattern, $URN, false);
        } catch (FunctionsEx $e) {
            throw new SelfEx("Произошла ошибка при обработке URN, для проверки доступа к режиму просмотра", 2003);
        }

        if ($documentType != $this->documentType) {
            throw new SelfEx("documentType из URN: '{$documentType}' не равен documentType: '{$this->documentType}', полученному при инициализации класса", 2004);
        }

        foreach ($this->modes as &$mode) {

            if ($mode['name'] == $modeName) {

                if (is_null($mode['result'])) {
                    $mode['result'] = call_user_func([$this->object, $mode['method']]);
                }
                return $mode['result'];
            }
        }
        unset($mode);

        throw new SelfEx("Определенный mode['name']: '{$modeName}' не найден среди массивов с режимами просмотра", 2005);
    }


    /**
     * Предназначен для получения ассоциативных массивов доступных режимов просмотра
     *
     * @return array ассоциативные массивы доступных режимов просмотра
     */
    public function getAvailableViewMode(): array
    {
        $result = [];

        foreach ($this->modes as &$mode) {

            if (is_null($mode['result'])) {
                $mode['result'] = call_user_func([$this->object, $mode['method']]);
            }
            if ($mode['result']) {

                $result[] = [
                    'name'  => $mode['name'],
                    'label' => $mode['label']
                ];
            }
        }
        unset($mode);
        return $result;
    }


    /**
     * Предназначен для перенаправления пользователя на страницу навигации
     *
     */
    static public function redirectToNavigation(): void
    {
        Session::setErrorMessage("Режим просмотра, на который Вы собираетесь перейти - недоступен");
        header('Location: /home/navigation');
        exit();
    }
}