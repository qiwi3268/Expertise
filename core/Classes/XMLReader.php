<?php


namespace core\Classes;
use Exception as SelfEx; //todo
use Lib\Singles\XMLValidator;
use SimpleXMLElement;


class XMLReader
{


    private SimpleXMLElement $data;
    private XMLValidator $validator;

    public function __construct()
    {
        if (($data = simplexml_load_file(SETTINGS . '/routes.xml')) === false) {
            throw new SelfEx("Ошибка при инициализации XML-схемы маршрутизации", 1);
        }
        $this->data = $data;
        $this->validator = new XMLValidator();
    }

    public function validatePages(): void
    {

        $test = $this->data->pages;
        $pages = $this->data->pages->page;

        foreach ($pages as $page) {

            //$this->validator->validateAttributes('');

            $dirs = $page->files->dir;

            foreach ($dirs as $dir) {

                 $this->validator->validateAttributes($dir,"<dir />", true, 'path', 'ext');


            }
        }
    }

}