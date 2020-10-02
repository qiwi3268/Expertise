<?php


namespace Classes\TotalCC\Actions;
use Lib\Form\HtmlDependenciesManager;
use Classes\Application\ApplicationFormHtmlDependenciesManager;


class DisplayDependenciesAction1 extends HtmlDependenciesManager
{
    private HtmlDependenciesManager $applicationForm;

    public function __construct()
    {
        $this->applicationForm = new ApplicationFormHtmlDependenciesManager();
        parent::__construct();
    }

    protected function initializeBlockDependencies(): void
    {
        $array = $this->applicationForm->getBlockDependencies();
        $this->blockDependencies = [
            'finance_type' => $array['finance_type'],
            'no_data'      => $array['no_data']
        ];
    }

    protected function initializeRequireDependencies(): void
    {
        return;
    }
}