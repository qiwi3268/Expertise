<?php


namespace Tables\Signs;


/**
 * Таблица: <i>'sign_grbs'</i>
 *
 */
class grbs implements Interfaces\SignTable
{

    static private string $tableName = 'sign_grbs';

    use Traits\SignTable;
}