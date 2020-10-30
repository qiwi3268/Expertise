<?php

use Lib\ViewModes\ViewModes;


$tmp_CLASS_CONST_namespace = '\Classes\ViewModes';

$viewModes = ViewModes::getInstance('application');

$result = $viewModes->checkAccessToViewModeByURN('home/expertise_cards/application/view_1');

vd($result);

$result = $viewModes->getAvailableViewModes();
vd($result);
//todo в хелпер по формированию ссылки сделать метод для формирования ссылок для доступные режимы просмотра

