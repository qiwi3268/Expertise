<?php


namespace Tables\Responsible\type_4;

use Tables\Responsible\type_4\Interfaces\ResponsibleType4;
use Tables\Responsible\type_4\Traits\ResponsibleType4 as ResponsibleType4Trait;


/**
 * Таблица: <i>'resp_section_documentation_1_type_4'</i>
 *
 * Ответственные пользователи к разделу для вида объекта "Производственные / непроизводственные"
 *
 */
final class section_documentation_1 implements ResponsibleType4
{

    static private string $tableName = 'resp_section_documentation_1_type_4';

    use ResponsibleType4Trait;
}