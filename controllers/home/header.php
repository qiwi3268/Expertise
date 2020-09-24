<?php


use core\Classes\Session;
use Lib\Singles\VariableTransfer;


$variablesTV = VariableTransfer::getInstance();

$variablesTV->setValue('userFIO', Session::getUserFullFIO());