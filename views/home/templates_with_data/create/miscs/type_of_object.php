<!--Вид объекта-->
<?php $_data = TemplateMaker::getSelfData(); ?>

<div class="form-field field" data-misc_field data-name="type_of_object" data-required="true">
    <span class="form-field__title field-title">Вид объекта</span>
    
    <div class="form-field__item">
        <div class="form-field__body">
            <div class="form-field__select field-select" data-misc_select>
                <span class="form-field__value field-value" data-misc_value>Выберите значение</span>
                <i class="form-field__icon-misc fas fa-bars"></i>
                <i class="form-field__icon-filled fas fa-check"></i>
            </div>
        </div>
        <span class="form-field__error field-error">Поле обязательно для заполнения</span>
    </div>
    
    <div class="modal" data-misc_modal data-result_callback="document_field">
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
    <input data-form="application" class="form-field__result field-result" type="hidden" data-misc_result name="type_of_object">
</div>
<!--//Вид объекта//-->