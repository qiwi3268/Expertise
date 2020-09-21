<?php


namespace Tables\Miscs;


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
    use Traits\SingleMiscValidate;
}
