<?php


namespace Tables\Miscs;


/**
 * Таблица: <i>'misc_type_of_object'</i>
 *
 * Справочник "Вид объекта"
 *
 */
final class type_of_object implements Interfaces\SingleMiscValidate
{

    static private string $tableName = 'misc_type_of_object';

    use Traits\SingleMisc;
    use Traits\SingleMiscValidate;
}
