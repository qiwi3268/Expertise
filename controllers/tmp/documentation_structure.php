<?php


$structure1 = structure_documentation1Table::getAllActive();
$FilesStructure1 = new NodeStructure($structure1);
$structure1TV = $FilesStructure1->getDepthStructure();


echo 'уберу тебе этот вывод :)';
//var_dump($structure1TV);
