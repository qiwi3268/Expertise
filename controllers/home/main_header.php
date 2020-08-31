<?php


$variablesTV = \Lib\Singles\VariableTransfer::getInstance();

$variablesTV->setValue('userFIO', \core\Classes\Session::getUserFullFIO());