<?php


namespace ControllersClasses\Home\ExpertiseCards\Sidebars;

use Lib\Exceptions\Responsible as ResponsibleEx;
use functions\Exceptions\Functions as FunctionsEx;

use ControllersClasses\Controller;
use Lib\Responsible\Responsible;


class Info extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     * @throws ResponsibleEx
     * @throws FunctionsEx
     */
    public function doExecute(): void
    {
        $responsible = new Responsible(CURRENT_DOCUMENT_ID, CURRENT_DOCUMENT_TYPE);

        // cr - current responsible
        $cr = $responsible->getCurrentResponsible();

        if ($cr['type'] != 'type_1') {

            list('type' => $cr_type, 'users' => $cr_users) = $cr;

            if ($cr_type == 'type_3') {
                $cr_labelTV = 'Сторона заявителя';
            } else {
                $cr_labelTV = 'ОГАУ "Госэкспертиза Челябинской области"';
            }

            $cr_usersTV = [];

            foreach ($cr_users as $user) {
                $cr_usersTV[] = getFIO($user, false);
            }

            $this->VT->setExistenceFlag('responsible', true);
            $this->VT->setValue('responsible_label', $cr_labelTV);
            $this->VT->setValue('responsible_users', $cr_usersTV);
        } else {

            $this->VT->setExistenceFlag('responsible', false);
        }
    }
}