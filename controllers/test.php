<?php


$federalProjects = TestTable::getAssocFederalProject();

foreach($federalProjects as $project){
   //TestTable::createFederalProjectForNationalProject($project['id_national_project'], $project['id']);
}

//var_dump($federalProjects);