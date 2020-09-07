<?php


namespace Lib\Singles\Helpers;


// Класс предназначен для обработки ответственных
class Responsible
{
    private array $responsible;

    // Принимает параметры-----------------------------------
    // todo
    public function __construct(array $responsible)
    {
        $this->responsible = $responsible;
    }

    //
    //
    public function getResponsibleToView(): array
    {
        $result = [];

        $responsible = $this->responsible;
        $responsibleType = $responsible['type'];

        if ($responsibleType == 'type_1') {
            $result['label'] = $HZ;
        }


        return $result;
    }


}