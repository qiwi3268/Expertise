<?php


namespace Tables\Miscs;

use Tables\CommonTraits\Existent as ExistentTrait;



/**
 * Таблица: <i>'misc_cultural_object_type'</i>
 *
 * Справочник "Тип объекта культурного наследия"
 *
 */
final class cultural_object_type implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_cultural_object_type';

    use Traits\SingleMisc;
    use ExistentTrait;
}
