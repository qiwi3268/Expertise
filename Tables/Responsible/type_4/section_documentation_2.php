<?php


namespace Tables\Responsible\type_4;

use Tables\Responsible\type_4\Interfaces\ResponsibleType4;
use Tables\Responsible\type_4\Traits\ResponsibleType4 as ResponsibleType4Trait;


/**
 * Таблица: <i>'resp_section_documentation_2_type_4'</i>
 *
 * Ответственные пользователи к разделу для вида объекта "Линейные"
 *
 */
final class section_documentation_2 implements ResponsibleType4
{

    static private string $tableName = 'resp_section_documentation_2_type_4';

    use ResponsibleType4Trait;
}