<?php


//$xml = new DOMDocument('1.0','utf-8');

$xml=new DomDocument('1.0','utf-8');
$xml->formatOutput = true;



$sorts = $xml->appendChild($xml->createElement('sorts'));

$sorts->setAttribute("align", "left");
$sorts->setAttribute("ha", "right");

$sort = $sorts->appendChild($xml->createElement('sort'));

$name = $sort->appendChild($xml->createElement('name'));

$name->appendChild($xml->createTextNode('Яблоко'));

$filePath = ROOT.'/uploads/goods.xml';

$xml->save($filePath);


$unloadName = basename($filePath);


//Сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
if(ob_get_level()){
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($unloadName));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit();

