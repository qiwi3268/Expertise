<!--Вид объекта-->
<?php $_data = TemplateMaker::getSelfData(); ?>

<div class="body-card__row field" data-misc_field data-name="type_of_object" data-required="true">
    <span class="body-card__title field-title">Вид объекта</span>
    
    <div class="body-card__item">
        <div class="body-card__field">
            <div class="body-card__select field-select" data-misc_select>
                <span class="body-card__value field-value" data-misc_value>Выберите значение</span>
                <i class="body-card__icon fas fa-bars"></i>
                <i class="body-card__icon-filled fas fa-check"></i>
            </div>
        </div>
        <span class="body-card__error field-error">Поле обязательно для заполнения</span>
    </div>
    
    <div class="modal" data-misc_modal data-result_callback="application_field">
        <i class="modal__close fas fa-times" data-misc_close></i>
        <div class="modal__items" data-misc_body>
            <?php foreach ($_data as $pageNumber => $page): ?>
                <div class="modal__page" data-misc_page="<?= $pageNumber ?>">
                    <?php foreach ($page as $item): ?>
                        <div class="modal__item" data-misc_item data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <input data-form="application" class="body-card__result field-result" type="hidden" data-misc_result name="type_of_object">
</div>
<!--//Вид объекта//-->