<?php


namespace PageControllers\Home\ExpertiseCards\Sidebars;

use Lib\Exceptions\ViewModes as ViewModesEx;
use Lib\Exceptions\XMLValidator as XMLValidatorEx;
use ReflectionException;

use core\Classes\ControllersInterface\PageController;
use Lib\ViewModes\ViewModes as MainViewModes;
use Lib\Singles\Helpers\PageAddress;


class ViewModes extends PageController
{

    /**
     * Реализация абстрактного метода
     *
     * @throws ViewModesEx
     * @throws XMLValidatorEx
     * @throws ReflectionException
     */
    public function doExecute(): void
    {
        $viewModes = MainViewModes::getInstance();

        $availableModesTV = [];

        foreach ($viewModes->getAvailableViewModes() as $mode) {

            $availableModesTV[] = [
                'label'      => $mode['label'],
                'ref'        => PageAddress::createCardRef(CURRENT_DOCUMENT_ID, CURRENT_DOCUMENT_TYPE, $mode['name']),
                'isSelected' => $mode['name'] == CURRENT_VIEW_MODE
            ];
        }
        $this->VT->setValue('available_view_modes', $availableModesTV);
    }
}