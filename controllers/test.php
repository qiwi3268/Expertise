<?php


use Classes\Application\Responsible;
use Tables\user;
use Tables\people_name;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;




for ($s = 1; $s < 5000000000; $s += 50000) {
    var_dump(getHumanFileSize($s));
}


