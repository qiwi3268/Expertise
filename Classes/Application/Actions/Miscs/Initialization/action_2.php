<?php


namespace Classes\Application\Actions\Miscs\Initialization;
use Lib\Miscs\Initialization\Initializer;


/**
 * Предназначен для инициализации справочников в действии "Назначить экспертов"
 *
 */
class action_2 extends Initializer
{

    protected const PAGINATION_SIZE = 8;


    /**
     * Конструктор класса
     *
     * Предназначен для инициализации имеющихся в форме справочников
     *
     * @param array|null $mainBlocks341 параметр передан со значением по умолчанию, чтобы не было конфикта
     * с абстрактным методом родительского класса
     */
    public function __construct(?array $mainBlocks341 = [])
    {
        $mainBlocks341 ??= [];

        $this->setSingleMisc('main_block_341', $mainBlocks341);
    }
}