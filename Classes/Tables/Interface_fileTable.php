<?php


interface Interface_fileTable{

     static public function deleteById(int $id):void;

     static public function setUploadedById(int $id):void;

     static public function getAssocById(int $id):?array;
}