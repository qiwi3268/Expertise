<?php


namespace PageControllers;

use core\Classes\ControllersInterface\PageController;


class HtmlHead extends PageController
{

    private const ROOT_CSS = '/views/css/';
    private const ROOT_LIB_CSS = '/views/css/lib/';
    private const TMP_CSS = '/views/css/tmp/';

    private const ROOT_JS = '/views/js/';
    private const ROOT_LIB_JS = '/views/js/lib/';
    private const TMP_JS = '/views/js/tmp/';
    private const APPLICATION_JS = '/views/js/application/';
    private const MODALS_JS = '/views/js/modals/';

    /**
     * Подключаемые файлы
     *
     */
    private array $sourcesFiles = [];


    /**
     * Реализация абстрактного метода
     *
     */
    public function doExecute(): void
    {
        // Название страницы
        $pageName = '';

        switch (URN) {

            case '' :
                $pageName = 'АИС';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'modal.css')
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addJsSource(self::ROOT_JS, 'ErrorHandler.js', 'BrowserHelper.js', 'login.js', 'API.js')
                    ->addJsSource(self::MODALS_JS, 'ErrorModal.js')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js');
                break;

            case 'home/application/create' :
                $pageName = 'АИС_create';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'header.css', 'footer.css', 'main.css', 'modal.css', 'radio.css', 'calendar.css', 'file_modal.css', 'files.css', 'documentation.css', 'multiple_block.css', 'sign_modal.css', 'create_application.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js', 'cadesplugin_api.js')
                    ->addJsSource(self::ROOT_JS, 'ErrorHandler.js', 'main.js', 'API.js', 'section.js', 'validation.js', 'DependenciesHandler.js', 'radio.js', 'PerfectCades.js', 'BrowserHelper.js', 'FileChecker.js', 'FileNeeds.js', 'MultipleBlock.js', 'FileField.js', 'GeFile.js', 'PartBlock.js')
                    ->addJsSource(self::MODALS_JS, 'Calendar.js', 'ErrorModal.js', 'FileUploader.js', 'Misc.js', 'SignView.js', 'SignHandler.js')
                    ->addJsSource(self::APPLICATION_JS, 'create.js', 'save.js', 'sidebar.js');
                break;

            case 'home/expertise_cards/application/view' :
                $pageName = 'АИС_view';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css', 'create_application.css', 'view_application.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::ROOT_JS, 'section.js', 'main.js', 'API.js', 'FileField.js', 'GeFile.js', 'ErrorHandler.js')
                    ->addJsSource(self::MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Tooltip.js');
                break;

            case 'home/application/edit' :
                $pageName = 'АИС';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'modal.css', 'calendar.css', 'create_application.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js', 'calendar.js', 'radio.js', 'save_application.js', 'test.js');
                break;

            case 'home/expertise_cards/total_cc/view' :
                $pageName = 'АИС_view';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css', 'comments_table.css', 'total_cc_view.css')
                    ->addJsSource(self::ROOT_JS, 'main.js', 'section.js', 'DependenciesHandler.js')
                    ->addJsSource(self::MODALS_JS, 'Tooltip.js', 'ErrorModal.js');
                break;

            case 'home/expertise_cards/section_documentation_1/view' :
                $pageName = 'АИС_view';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'sign_modal.css', 'action_sidebar.css', 'tooltip.css', 'comments_table.css', 'section_view.css', 'statistic.css')
                    ->addJsSource(self::ROOT_JS, 'main.js', 'section.js', 'FileField.js', 'GeFile.js')
                    ->addJsSource(self::MODALS_JS, 'Tooltip.js', 'ErrorModal.js', 'SignView.js');
                break;

            case 'tmp/form_to_file_download' :
                $pageName = 'Загрузка файлов на сервер';
                $this->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::TMP_JS, 'file_download.js');
                break;

            case 'tmp/documentation_structure' :
                $pageName = 'Документация';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'files.css', 'documentation.css');
                break;

            case 'home/navigation' :
                $pageName = 'Навигация';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'navigation.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::ROOT_JS, 'sorting.js');
                break;

            case 'home/expertise_cards/application/actions/action_2' :
                $pageName = 'Action_2';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css', 'files.css', 'sign_modal.css', 'modal.css','action_2.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::APPLICATION_JS . 'actions/', 'action_2.js')
                    ->addJsSource(self::ROOT_JS, 'main.js', 'API.js', 'ErrorHandler.js', 'FileField.js', 'GeFile.js', 'Drag&Drop.js' , 'section.js')
                    ->addJsSource(self::MODALS_JS,'SignView.js', 'Misc.js', 'ErrorModal.js');
                break;

            case 'home/test_1' :
                $pageName = 'Тест';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_sidebar.css', 'test_1.css', 'radio.css', 'files.css', 'comment_groups.css', 'comment_modal.css', 'group_modal.css','action_header.css', 'comments_table.css')
                    ->addJsSource(self::ROOT_JS, 'ErrorHandler.js')
                    ->addJsSource(self::MODALS_JS,'ErrorModal.js', 'CommentGroup.js')
                    ->addJsSource(self::TMP_JS, 'test_1.js');
                break;
    
            case 'home/conclusion' :
                $pageName = 'Тест';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_sidebar.css', 'radio.css', 'files.css', 'action_header.css', 'create_application.css', 'view_application.css', 'action_sidebar.css', 'conclusion.css')
                    ->addJsSource(self::ROOT_JS, 'main.js', 'section.js')
                    ->addJsSource(self::TMP_JS, 'conclusion.js', 'section.js');
                break;
                

            case 'home/expertise_cards/section_documentation_1/actions/action_1' :
                $pageName = 'Раздел';
                $this->addFontAwesome5Case()
                    ->addTinyMCECase()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css', 'radio.css', 'files.css', 'documentation.css', 'multiple_block.css', 'modal.css', 'sign_modal.css', 'tooltip.css', 'create_application.css', 'comment_modal.css', 'comments_table.css','section_create_test.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::ROOT_JS, 'section.js', 'main.js', 'API.js', 'DependenciesHandler.js', 'MultipleBlock.js', 'PartBlock.js', 'radio.js', 'FileField.js', 'GeFile.js', 'validation.js', 'CommentsTable.js', 'GeComment.js')
                    ->addJsSource(self::MODALS_JS,'ErrorModal.js', 'CommentCreator.js', 'SignView.js', 'Misc.js', 'Tooltip.js')
                    ->addJsSource(self::ROOT_JS . 'expertise_cards/documents/section/actions/', 'action_1.js');
                break;

            case 'home/expertise_cards/total_cc/actions/action_1' :
                $pageName = 'Общая часть';
                $this->addFontAwesome5Case()
                    ->addCssSource(self::ROOT_CSS, 'entry.css')
                    ->addCssSource(self::TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css', 'multiple_block.css', 'create_application.css', 'action_3.css', 'modal.css', 'radio.css')
                    ->addJsSource(self::ROOT_LIB_JS, 'lib_XHR.js')
                    ->addJsSource(self::ROOT_JS, 'main.js', 'API.js', 'section.js', 'DependenciesHandler.js', 'radio.js', 'MultipleBlock.js', 'PartBlock.js', 'validation.js')
                    ->addJsSource(self::MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Misc.js')
                    ->addJsSource(self::ROOT_JS . 'total_cc/actions/', 'action_1.js');
                break;

        }

        $this->VT->setValue('page_name', $pageName);
        $this->VT->setValue('sources_files', $this->sourcesFiles);
    }


    /**
     * Предназначен для добавления css ресурса
     *
     * @param string $path путь к файлу в ФС сервера, либо '', если ссылка на внешний ресурс
     * @param string ...$names <i>перечисление</i> названий файлов
     * @return $this
     */
    private function addCssSource(string $path, string ...$names): self
    {
        $arr = [];

        foreach ($names as $name) {
            $arr[] = "<link rel=\"stylesheet\" href=\"{$path}{$name}\">";
        }

        $this->sourcesFiles = [...$this->sourcesFiles, ...$arr];
        return $this;
    }


    /**
     * Предназначен для добавления js ресурса
     *
     * @param string $path путь к файлу в ФС сервера, либо '', если ссылка на внешний ресурс
     * @param string ...$names <i>перечисление</i> названий файлов
     * @return $this
     */
    private function addJsSource(string $path, string ...$names): self
    {
        $arr = [];

        foreach ($names as $name) {
            $arr[] = "<script src=\"{$path}{$name}\"></script>";
        }

        $this->sourcesFiles = [...$this->sourcesFiles, ...$arr];
        return $this;
    }


    /**
     * Предназначен для добавления библиотеки font awesome 5
     *
     * @return $this
     */
    private function addFontAwesome5Case(): self
    {
        return $this->addCssSource(self::ROOT_LIB_CSS . 'fontawesome-free-5.13.0-web/css/', 'all.min.css');
    }


    /**
     * Предназначен для добавления библиотеки tinymce
     *
     * @return $this
     */
    private function addTinyMCECase(): self
    {
        return $this->addJsSource(self::ROOT_LIB_JS . 'tinymce/js/tinymce/', 'tinymce.min.js');
    }
}