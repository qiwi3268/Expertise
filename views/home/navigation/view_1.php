<!-- view отображения заявления в табличной форме -->
<?php $variablesTV = VariableTransfer::getInstance(); ?>


    <div class="navigation__body">
        <div class="navigation__table table-navigation">
            <div class="table-navigation__controller">
                <div class="navigation-table__size-header">
                    <?php foreach ($variablesTV->getValue('navigationDataPerPage') as $amount): ?>
                        <span class="navigation-table__amount" data-is_selected="<?= $amount['is_selected'] ?>" data-per_page="<?= $amount['data_per_page'] ?>"><?= $amount['description'] ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="navigation-table__sort-header">
                    <?php foreach ($variablesTV->getValue('navigationSorting') as $category): ?>
                        <span class="navigation-table__category" data-sort_name="<?= $category['sort_name'] ?>"
                            <?php if ($category['is_selected']): ?>
                                data-sort_type="<?= $category['sort_type'] ?>"
                            <?php endif; ?>>
                            <?= $category['description'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="table-navigation__row table-navigation__header">
                <div class="table-navigation__column table-navigation__title">
                    <span class="table-navigation__text">id-заявления</span>
<!--                    <i class="table-navigation__icon-sort fas fa-angle-down"></i>-->
                </div>
                <div class="table-navigation__column table-navigation__title">
                    <span class="table-navigation__text">Номер заявления</span>
<!--                    <i class="table-navigation__icon-sort fas fa-angle-down"></i>-->
                </div>
            </div>
            <?php foreach ($variablesTV->getValue('navigationData') as $app): ?>
                <div class="table-navigation__row">
                    <div class="table-navigation__column"><?= $app['id'] ?></div>
                    <a class="table-navigation__column" target="_blank" href="/home/application/view?id_application=<?= $app['id'] ?>"><?= $app['numerical_name'] ?></a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
    <input name="navigation__view-name" type="hidden" value="<?= $variablesTV->getValue('viewName') ?>">






</div>