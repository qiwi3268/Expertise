<?php


use core\Classes\Session;
use Lib\Singles\VariableTransfer;


$VT = VariableTransfer::getInstance();

$VT->setValue('user_FIO', Session::getUserFullFIO());