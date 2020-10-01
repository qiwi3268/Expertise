<?php


namespace Classes\TotalCC\Actions;
use Lib\Form\DisplayDependencies;
use Classes\Application\DisplayDependenciesApplicationForm;


class DisplayDependenciesAction1 extends DisplayDependencies
{
    private DisplayDependencies $applicationForm;

    public function __construct()
    {
        $this->applicationForm = new DisplayDependenciesApplicationForm();
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