<?php


namespace ControllersClasses;


class HtmlHead extends Controller
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
    public function execute(): void
    {
        // Название страницы
        $pageName = '';

        switch (URN) {

            case '' :
                $pageName = 'АИС';
                $this->addFontAwesome5Case()
                    ->addCssSource(TMP_CSS, 'null.css', 'main.css', 'modal.css')
                    ->addCssSource(ROOT_CSS, 'entry.css')
                    ->addJsSource(ROOT_JS, 'ErrorHandler.js', 'BrowserHelper.js', 'API_login.js')
                    ->addJsSource(MODALS_JS, 'ErrorModal.js')
                    ->addJsSource(ROOT_LIB_JS, 'lib_XHR.js');
                break;

            case 'home/application/create' :
                $pageName = 'АИС_create';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'header.css', 'footer.css', 'main.css', 'modal.css', 'radio.css', 'calendar.css', 'file_modal.css', 'files.css', 'documentation.css', 'multiple_block.css', 'sign_modal.css'),
                    ...CreateSource('css', TMP_CSS, 'create_application.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js', 'cadesplugin_api.js'),
                    ...CreateSource('js', ROOT_JS, 'ErrorHandler.js', 'main.js', 'API.js', 'section.js', 'validation.js', 'DependenciesHandler.js', 'radio.js', 'PerfectCades.js', 'BrowserHelper.js', 'FileChecker.js', 'FileNeeds.js', 'MultipleBlock.js', 'GeFile.js', 'PartBlock.js'),
                    ...CreateSource('js', MODALS_JS, 'Calendar.js', 'ErrorModal.js', 'FileUploader.js', 'Misc.js', 'SignView.js', 'SignHandler.js'),
                    ...CreateSource('js', APPLICATION_JS, 'create.js', 'save.js', 'sidebar.js'),
                ];
                break;

            case 'home/expertise_cards/application/view' :
                $pageName = 'АИС_view';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css'),
                    ...CreateSource('css', TMP_CSS, 'create_application.css', 'view_application.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', ROOT_JS, 'section.js', 'main.js', 'API.js', 'GeFile.js', 'ErrorHandler.js'),
                    ...CreateSource('js', MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Tooltip.js'),
                ];
                break;

            case 'home/application/edit' :
                $pageName = 'АИС';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'modal.css', 'calendar.css'),
                    ...CreateSource('css', TMP_CSS, 'create_application.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', TMP_JS, 'sidebar.js', 'section.js', 'validation.js', 'cards.js', 'calendar.js', 'radio.js', 'save_application.js', 'test.js')
                ];
                break;

            case 'home/expertise_cards/total_cc/view' :
                $pageName = 'АИС_view';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'action_sidebar.css', 'radio.css', 'sign_modal.css', 'tooltip.css', 'comments_table.css', 'total_cc_view.css'),
                    ...CreateSource('js', ROOT_JS, 'main.js', 'section.js', 'DependenciesHandler.js'),
                    ...CreateSource('js', MODALS_JS, 'Tooltip.js', 'ErrorModal.js'),
                ];
                break;


            case 'home/expertise_cards/section_documentation_1/view' :
                $pageName = 'АИС_view';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'create_application.css', 'view_application.css', 'header.css', 'footer.css', 'files.css', 'documentation.css', 'sign_modal.css', 'action_sidebar.css', 'tooltip.css', 'comments_table.css', 'section_view.css', 'statistic.css'),
                    ...CreateSource('js', ROOT_JS, 'main.js', 'section.js', 'GeFile.js'),
                    ...CreateSource('js', MODALS_JS, 'Tooltip.js', 'ErrorModal.js', 'SignView.js'),
                ];
                break;

            case 'tmp/form_to_file_download' :
                $pageName = 'Загрузка файлов на сервер';
                $sourcesFiles = [...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', TMP_JS, 'file_download.js')
                ];
                break;

            case 'tmp/documentation_structure' :
                $pageName = 'Документация';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'files.css'),
                    ...CreateSource('css', TMP_CSS, 'documentation.css')
                ];
                break;

            case 'home/navigation' :
                $pageName = 'Навигация';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css'),
                    ...CreateSource('css', TMP_CSS, 'navigation.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', ROOT_JS, 'sorting.js')
                ];
                break;

            case 'home/expertise_cards/application/actions/action_2' :
                $pageName = 'Action_2';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
                    ...CreateSource('css', TMP_CSS, 'files.css', 'sign_modal.css', 'modal.css','action_2.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', APPLICATION_JS . 'actions/', 'action_2.js'),
                    ...CreateSource('js', ROOT_JS, 'main.js', 'API.js', 'ErrorHandler.js', 'GeFile.js', 'Drag&Drop.js' , 'section.js'),
                    ...CreateSource('js', MODALS_JS,'SignView.js', 'Misc.js', 'ErrorModal.js')
                ];
                break;

            case 'home/test_1' :
                $pageName = 'Тест';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css'),
                    ...CreateSource('css', TMP_CSS, 'action_sidebar.css', 'test_1.css', 'radio.css', 'files.css', 'comment_groups.css', 'comment_modal.css', 'group_modal.css','action_header.css', 'comments_table.css'),
                    ...CreateSource('js', ROOT_JS, 'ErrorHandler.js'),
                    ...CreateSource('js', MODALS_JS,'ErrorModal.js', 'CommentGroup.js'),
                    ...CreateSource('js', TMP_JS, 'test_1.js'),
                ];
                break;

            case 'home/expertise_cards/section_documentation_1/actions/action_1' :
                $pageName = 'Раздел';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...GetTinyMCECase(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
                    ...CreateSource('css', TMP_CSS,'radio.css', 'files.css', 'documentation.css', 'multiple_block.css', 'modal.css', 'sign_modal.css', 'tooltip.css', 'create_application.css', 'comment_modal.css', 'comments_table.css','section_create_test.css'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', ROOT_JS, 'section.js', 'main.js', 'API.js', 'DependenciesHandler.js', 'MultipleBlock.js', 'PartBlock.js', 'radio.js', 'GeFile.js', 'validation.js', 'CommentsTable.js', 'GeComment.js'),
                    ...CreateSource('js', MODALS_JS,'ErrorModal.js', 'CommentCreator.js', 'SignView.js', 'Misc.js', 'Tooltip.js'),
                    ...CreateSource('js', ROOT_JS . 'expertise_cards/documents/section/actions/', 'action_1.js'),

                ];
                break;

            case 'home/expertise_cards/total_cc/actions/action_1' :
                $pageName = 'Общая часть';
                $sourcesFiles = [...GetFontAwesome5Case(),
                    ...CreateSource('css', ROOT_CSS, 'entry.css'),
                    ...CreateSource('css', TMP_CSS, 'null.css', 'main.css', 'header.css', 'footer.css', 'action_header.css'),
                    ...CreateSource('css', TMP_CSS,'multiple_block.css', 'create_application.css', 'action_3.css', 'modal.css', 'radio.css'),
                    ...CreateSource('js', ROOT_JS, 'main.js', 'API.js', 'section.js', 'DependenciesHandler.js', 'radio.js', 'MultipleBlock.js', 'PartBlock.js', 'validation.js'),
                    ...CreateSource('js', ROOT_LIB_JS, 'lib_XHR.js'),
                    ...CreateSource('js', MODALS_JS, 'ErrorModal.js', 'SignView.js', 'Misc.js'),
                    ...CreateSource('js', ROOT_JS . 'total_cc/actions/', 'action_1.js'),
                ];
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