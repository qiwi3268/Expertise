<?php


namespace Tables\Responsible\type_3;

use Tables\Responsible\type_3\Interfaces\ResponsibleType3;
use Tables\Responsible\type_3\Traits\ResponsibleType3 as ResponsibleType3Trait;


/**
 * Таблица: <i>'resp_application_type_3'</i>
 *
 * Ответственные группы заявителей к заявлению
 *
 */
final class application implements ResponsibleType3
{

    static private string $tableName = 'resp_application_type_3';

    use ResponsibleType3Trait;
}



