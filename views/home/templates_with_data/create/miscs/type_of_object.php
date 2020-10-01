<!--Вид объекта-->
<?php $_data = TemplateMaker::getSelfData(); ?>

<div class="application-field field" data-misc_field data-name="type_of_object" data-required="true">
    <span class="application-field__title field-title">Вид объекта</span>
    
    <div class="application-field__item">
        <div class="application-field__body">
            <div class="application-field__select field-select" data-misc_select>
                <span class="application-field__value field-value" data-misc_value>Выберите значение</span>
                <i class="application-field__icon-misc fas fa-bars"></i>
                <i class="application-field__icon-filled fas fa-check"></i>
            </div>
        </div>
        <span class="application-field__error field-error">Поле обязательно для заполнения</span>
    </div>
    
    <div class="modal" data-misc_modal data-result_callback="application_field">
        <i class="modal__close fas fa-times" data-misc_close></i>
        <div class="modal__items" data-misc_body>
            <?php foreach ($variablesTV->getValue('type_of_object') as $pageNumber => $page): ?>
                <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                    <?php foreach ($page as $item): ?>
                        <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <input data-form="application" class="application-field__result field-result" type="hidden" data-misc_result name="type_of_object">
</div>
<!--//Вид объекта//-->