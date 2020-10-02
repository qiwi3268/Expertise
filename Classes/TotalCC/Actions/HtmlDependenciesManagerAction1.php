<?php


namespace Classes\TotalCC\Actions;
use Lib\Form\HtmlDependenciesManager;
use Classes\Application\HtmlDependenciesManagerApplicationForm;


/**
 * Предназначен для инициализации и предоставления зависимостей
 * в html-форме действия "Создать общую часть"
 *
 */
class HtmlDependenciesManagerAction1 extends HtmlDependenciesManager
{

    /**
     * Объект зависимостей в html-форме создания заявления
     *
     */
    private HtmlDependenciesManager $applicationForm;


    /**
     * Конструктор класса
     *
     *  @uses \Classes\Application\HtmlDependenciesManagerApplicationForm
     */
    public function __construct()
    {
        $this->applicationForm = new HtmlDependenciesManagerApplicationForm();
        parent::__construct();
    }


    /**
     * Реализация абстрактного метода
     *
     * @uses \Classes\Application\HtmlDependenciesManagerApplicationForm::getRequireDependencies()
     */
    protected function initializeBlockDependencies(): void
    {
        $array = $this->applicationForm->getBlockDependencies();

        $this->blockDependencies = [
            'financing_type'           => $array['financing_type'],
            'financing_source_no_data' => $array['financing_source_no_data']
        ];
    }

    /**
     * Реализация абстрактного метода
     *
     */
    protected function initializeRequireDependencies(): void
    {
        return;
    }
}