<?php


namespace Tables\Signs;


class grbs implements Interfaces\SignTable
{

    static private string $tableName = 'sign_grbs';

    use Traits\SignTable;
}