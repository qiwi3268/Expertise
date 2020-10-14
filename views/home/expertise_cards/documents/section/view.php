<!--todo переделать в VT-->
<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php $_TEPsByAuthors = $_VT->getValue('TEPsByAuthors'); ?>


<div class="view-section">
    
    <div class="view-section__statistics statistics">
        
        <div class="statistics__card">
            <div class="statistics__header">Замечания по типу</div>
            <div class="diagram statistics__diagram" data-col_width="30">
                <div class="diagram__body">
                    <div class="diagram__col" data-color="blue">
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="red">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="green">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="yellow">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="purple">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">10</span>
                        <span class="diagram__label">Всего</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">6</span>
                        <span class="diagram__label">Активные</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">4</span>
                        <span class="diagram__label">Снятые</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">2</span>
                        <span class="diagram__label">Рассмотрение эксперта</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">4</span>
                        <span class="diagram__label">Сторона заявителя</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="statistics__card">
            <div class="statistics__header">Критичность всех замечаний</div>
            <div class="diagram statistics__diagram" data-col_width="30">
                <div class="diagram__body">
                    <div class="diagram__col" data-color="green">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="blue">
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="red">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">4</span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">6</span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">1</span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="statistics__card">
            <div class="statistics__header">Критичность активных замечаний</div>
            <div class="diagram statistics__diagram" data-col_width="30">
                <div class="diagram__body">
                    <div class="diagram__col" data-color="green">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="blue">
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                    <div class="diagram__col" data-color="red">
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row"></div>
                        <div class="diagram__row filled"></div>
                    </div>
                </div>
                <div class="diagram__footer">
                    <div class="diagram__col">
                        <span class="diagram__number">2</span>
                        <span class="diagram__label">Техническая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">5</span>
                        <span class="diagram__label">Критическая ошибка</span>
                    </div>
                    <div class="diagram__col">
                        <span class="diagram__number">1</span>
                        <span class="diagram__label">Неустранимая ошибка</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fab fa-sistrix"></i>
                <div class="view-section__amount">10</div>
            </div>
            <div class="view-section__title">Замечания на рассмотрении эксперта</div>
        </div>
        <div class="view-section__body card-body expanded">
            <table id="comments_table" class="comments-table">
                <thead class="comments-table__header">
                <tr>
                    <th style="width: 1%">№</th>
                    <th style="width: 7%">Автор</th>
                    <th style="width: 25%">Текст замечания</th>
                    <th style="width: 10%">Нормативный документ</th>
                    <th style="width: 22%">Отмеченные файлы</th>
                    <th style="width: 25%">Ответ заявителя</th>
                </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border red">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>1. Замечания по ИОС1. ВР. ЛС02-1-17, ИОС2. ВР. ЛС02-1-29, ИОС3. ВР. ЛС02-1-28, ИОС4. ВР. ЛС02-1-31, ИОС5. ВР. ЛС02-1-33, ИОС6. ВР. ЛС02-1-32, ИОС7. ВР. ЛС02-1-30, ИОС1. ВР. ЛС02-2-12 (Системы электроснабжения)</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">
                            
                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">
                    
                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border green">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">
                    
                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                    <tr class="comments-table__row" data-comment_hash="1602587915838">
                        <td class="comments-table__criticality-border">123</td>
                        <td>
                            <div  class="comments-table__text">Автор</div>
                        </td>
                        <td>asdsa</td>
                        <td>dsadsa</td>
                        <td class="comments-table__files">
                            <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                                 data-mapping_level_1="2" data-mapping_level_2="1">
                                <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                     data-state="not_signed">
                                    <div class="files__info">
                    
                                        <i class="files__icon fas fa-file-excel"></i>
                                        <div class="files__description">
                                            <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                            <span class="files__size">168,75 Кб</span>
                                        </div>
                                    </div>
                                    <div class="files__state short">
                                        <i class="files__state-icon fas fa-pen-alt"></i>
                                    </div>
                                    <div class="files__actions">
                                        <i class="files__unload fas fa-angle-double-down"></i>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>Исправлено что-то</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fas fa-briefcase"></i>
                <div class="view-section__amount">4</div>
            </div>
            <div class="view-section__title">Замечания на стороне заявителя</div>
            
        </div>
        <div class="view-section__body card-body expanded">
            <table id="comments_table" class="comments-table">
                <thead class="comments-table__header">
                <tr>
                    <th style="width: 1%">№</th>
                    <th style="width: 7%">Автор</th>
                    <th style="width: 25%">Текст замечания</th>
                    <th style="width: 10%">Нормативный документ</th>
                    <th style="width: 22%">Отмеченные файлы</th>
                    <th style="width: 25%">Ответ эксперта</th>
                </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border red">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>1. Замечания по ИОС1. ВР. ЛС02-1-17, ИОС2. ВР. ЛС02-1-29, ИОС3. ВР. ЛС02-1-28, ИОС4. ВР. ЛС02-1-31, ИОС5. ВР. ЛС02-1-33, ИОС6. ВР. ЛС02-1-32, ИОС7. ВР. ЛС02-1-30, ИОС1. ВР. ЛС02-2-12 (Системы электроснабжения)</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border green">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__label">
                <i class="view-section__icon-label fas fa-check"></i>
                <div class="view-section__amount">4</div>
            </div>
            <div class="view-section__title">Снятые замечания</div>
        
        </div>
        <div class="view-section__body card-body expanded">
            <table id="comments_table" class="comments-table">
                <thead class="comments-table__header">
                <tr>
                    <th style="width: 1%">№</th>
                    <th style="width: 7%">Автор</th>
                    <th style="width: 25%">Текст замечания</th>
                    <th style="width: 10%">Нормативный документ</th>
                    <th style="width: 22%">Отмеченные файлы</th>
                    <th style="width: 25%">Вывод</th>
                </tr>
                </thead>
                <tbody id="comments_table_body" class="comments-table__body">
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border red">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>1. Замечания по ИОС1. ВР. ЛС02-1-17, ИОС2. ВР. ЛС02-1-29, ИОС3. ВР. ЛС02-1-28, ИОС4. ВР. ЛС02-1-31, ИОС5. ВР. ЛС02-1-33, ИОС6. ВР. ЛС02-1-32, ИОС7. ВР. ЛС02-1-30, ИОС1. ВР. ЛС02-2-12 (Системы электроснабжения)</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border green">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                <tr class="comments-table__row" data-comment_hash="1602587915838">
                    <td class="comments-table__criticality-border">123</td>
                    <td>
                        <div  class="comments-table__text">Автор</div>
                    </td>
                    <td>asdsa</td>
                    <td>dsadsa</td>
                    <td class="comments-table__files">
                        <div class="documentation__files files filled" data-comment_files="" data-id_file_field="1"
                             data-mapping_level_1="2" data-mapping_level_2="1">
                            <div class="files__item" data-read_only="true" data-id="1078" data-validate_results=""
                                 data-state="not_signed">
                                <div class="files__info">
                                    
                                    <i class="files__icon fas fa-file-excel"></i>
                                    <div class="files__description">
                                        <span class="files__name">Отчет Объекты в работе от 08.09.2020.xlsx</span>
                                        <span class="files__size">168,75 Кб</span>
                                    </div>
                                </div>
                                <div class="files__state short">
                                    <i class="files__state-icon fas fa-pen-alt"></i>
                                </div>
                                <div class="files__actions">
                                    <i class="files__unload fas fa-angle-double-down"></i>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>Исправлено что-то</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php foreach ($_VT->getValue('descriptions') as $author => $description): ?>
        <?php if (!empty($description)): ?>
            <div class="view-section__card">
                <div class="view-section__header"><?= $author ?></div>
                <div class="view-section__body">
                    <div class="view-section__description"><?= $description ?></div>
                </div>
            </div>
        <?php endif;?>
    <?php endforeach; ?>
    <div class="view-section__card card">
        <div class="view-section__header card-expand">
            <div class="view-section__title">Технико-экономические показатели</div>
        </div>
        <div class="view-section__body card-body expanded">
            <?php if (!empty($_TEPsByAuthors)): ?>
                <table class="tep-table">
                    <thead class="tep-table__header">
                        <tr>
                            <th class="tep-table__author">Автор</th>
                            <th>Показатель</th>
                            <th>Значение</th>
                            <th>Примечание</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($_TEPsByAuthors as $author => $TEPs): ?>
                        <tr>
                            <td class="tep-table__author" rowspan="<?= count($TEPs) ?>"><?= $author ?></td>
                            <td><?= $TEPs[0]['indicator'] ?></td>
                            <td><?= $TEPs[0]['value'] ?></td>
                            <td><?= $TEPs[0]['note'] ?></td>
                        </tr>
                        <?php for ($l = 1; $l < count($TEPs); $l++): ?>
                            <tr>
                                <td><?= $TEPs[$l]['indicator'] ?></td>
                                <td><?= $TEPs[$l]['value'] ?></td>
                                <td><?= $TEPs[$l]['note'] ?></td>
                            </tr>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    

    
    
</div>