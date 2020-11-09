<?php


namespace PageControllers\Home;

use core\Classes\ControllersInterface\PageController;
use core\Classes\Request\HttpRequest;
use Lib\Files\Unloader;


/**
 * Ошибки в этом скрипте в максимальной степени нежелательны
 *
 * Т.к. пользователь со своей рабочей страницы будет перенаправлен на эту с ошибкой
 * и все несохраненные данные пропадут
 *
 */
class FileUnloader extends PageController
{

    private const ADM_MESSAGE = 'Пожалуйста, обратитесь в администрацию системы с текстом или скришотом данной ошибки';
    private const URI_MESSAGE = 'Ваш запрос: ' . URI;

    /**
     * Составные части сообщения об ошибке
     *
     */
    private array $message = [];


    /**
     * Реализация абстрактного метода
     *
     */
    public function doExecute(): void
    {
        if (!$this->request->checkRequestMethod(HttpRequest::GET)) {

            $this->add("ОШИБКА. Метод запроса на сервер должен быть GET", 'h1')
                 ->add(self::ADM_MESSAGE);
        } elseif (!$this->request->has('fs_name', 'file_name')) {

            $this->add("ОШИБКА. Нет обязательных параметров GET запроса", 'h1')
                 ->add(self::URI_MESSAGE)
                 ->add(self::ADM_MESSAGE);
        } elseif (!file_exists($this->request->fs_name)) {

            $this->add("ОШИБКА. Указанный файл не существует в ФС сервера", 'h1')
                 ->add(self::URI_MESSAGE)
                 ->add(self::ADM_MESSAGE);
        } elseif (empty($this->request->file_name)) {

            $this->add("ОШИБКА. Передано пустое имя файла для выгрузки", 'h1')
                 ->add(self::URI_MESSAGE)
                 ->add(self::ADM_MESSAGE);
        }
        empty($this->message) ? Unloader::unload($this->request->fs_name, $this->request->file_name) : $this->exit();
    }


    /**
     * Предназначен для добавления сообщения об ошибке
     *
     * @param string $text текст сообщения
     * @param string $h тег h
     * @return $this
     */
    private function add(string $text, string $h = 'h4'): self
    {
        $this->message[] = "<{$h}>{$text}</{$h}>";
        return $this;
    }


    /**
     * Предназначен для завершения работы скрипта в случае обнаружения ошибки
     *
     */
    private function exit(): void
    {
        exit(implode('<br/>', $this->message));
    }
}