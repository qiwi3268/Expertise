<?php


namespace Classes\Application\Actions\Miscs\Initialization;
use Lib\Miscs\Initialization\Initializer;


/**
 * Предназначен для инициализации справочников в действии "Назначить экспертов"
 *
 */
class action_2 extends Initializer
{

    /**
     * Конструктор класса
     *
     * Предназначен для инициализации имеющихся в форме справочников
     *
     * @param array $mainBlocks341 разделы из 341 приказа
     * @param int $paginationSize количество справочников на стрнице
     */
    public function __construct(array $mainBlocks341, int $paginationSize = 8)
    {
        $this->singleMiscs['main_block_341'] = $mainBlocks341;
        $this->paginationSize = $paginationSize;
    }
}