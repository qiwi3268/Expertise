<?php


namespace Tables\Miscs;
use Tables\Miscs\Interfaces\SingleMisc;
use Tables\Miscs\Interfaces\SingleMiscValidate;
use Tables\CommonTraits\Existent as ExistentTrait;


/**
 * Таблица: <i>'misc_type_of_object'</i>
 *
 * Справочник "Вид объекта"
 *
 */
final class type_of_object implements SingleMisc, SingleMiscValidate
{

    static private string $tableName = 'misc_type_of_object';

    use Traits\SingleMisc;
    use ExistentTrait;
}
