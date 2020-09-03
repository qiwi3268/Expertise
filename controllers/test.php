<?php

use Tables\users;
use Tables\people_name;

$test = users::getActiveExperts();
var_dump($test);