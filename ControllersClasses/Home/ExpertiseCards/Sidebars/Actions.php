<?php


namespace ControllersClasses\Home\ExpertiseCards\Sidebars;


use Lib\Exceptions\Actions as ActionsEx;
use Lib\Exceptions\DataBase as DataBaseEx;
use Lib\Exceptions\DocumentTreeHandler as DocumentTreeHandlerEx;
use Tables\Exceptions\Tables as TablesEx;
use ReflectionException;

use ControllersClasses\Controller;
use Lib\Actions\Locator;


class Actions extends Controller
{

    /**
     * Реализация абстрактного метода
     *
     * @throws ActionsEx
     * @throws DataBaseEx
     * @throws DocumentTreeHandlerEx
     * @throws TablesEx
     * @throws ReflectionException
     */
    public function doExecute(): void
    {
        $availableActions = Locator::getInstance(CURRENT_DOCUMENT_TYPE)
            ->getObject()
            ->getAccessActions()
            ->getAvailableActions();

        $availableActionsTV = [];

        foreach ($availableActions as $action) {

            $availableActionsTV[] = [

                'ref'   => "/{$action['page_name']}?id_document=" . CURRENT_DOCUMENT_ID,
                'label' => $action['name']
            ];
        }

        $this->VT->setValue('available_actions', $availableActionsTV);
    }
}