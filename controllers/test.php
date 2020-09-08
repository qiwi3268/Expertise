<?php

use Exception as SelfEx;
use Lib\Responsible\Responsible;
use Lib\Responsible\XMLReader;
use Tables\user;
use Tables\people_name;
use Tables\application;
use Tables\Responsible\type_3\application as resp_application_type_3;
use Tables\applicant_access_group;
use Tables\Actions\application as ApplicationActions;



abstract class MainExecutionActions
{






}




class ApplicationAccessActions extends MainAccessActions
{
    public function action_1(): bool
    {

    }

    public function action_2(): bool
    {

    }
}

class ApplicationExecutionActions extends MainExecutionActions
{
    public function action_1(): string
    {

    }

    public function action_2(): string
    {

    }
}

var_dump(bin2hex(random_bytes(40)));
var_dump(bin2hex(random_bytes(40)));