<?php $variablesTV = VariableTransfer::getInstance(); ?>

<div class="navigation__body">
    <div class="navigation__table table-navigation">
        <div class="table-navigation__controller">
            <div class="navigation-table__pagination pagination-table">
                <?php if ($variablesTV->getExistenceFlag('pagination_PreviousPage')): ?>
                    <a class="pagination-table__icon" href="<?= $variablesTV->getValue('pagination_PreviousPageRef') ?>">
                        <i class="pagination-table__arrow fas fa-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <i class="pagination-table__arrow inactive fas fa-chevron-left"></i>
                <?php endif; ?>
                <span class="pagination-table__current-page"><?= $variablesTV->getValue('pagination_CurrentPage') ?></span>
                <?php if ($variablesTV->getExistenceFlag('pagination_NextPage')): ?>
                    <a class="pagination-table__icon" href="<?= $variablesTV->getValue('pagination_NextPageRef') ?>">
                        <i class="pagination-table__arrow fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <i class="pagination-table__arrow inactive fas fa-chevron-right"></i>
                <?php endif; ?>
            </div>
            <div class="navigation-table__size-header">
                <?php foreach ($variablesTV->getValue('navigationDataPerPage') as $amount): ?>
                    <span class="navigation-table__amount" data-is_selected="<?= $amount['is_selected'] ?>" data-per_page="<?= $amount['data_per_page'] ?>"><?= $amount['description'] ?></span>
                <?php endforeach; ?>
            </div>
            <div class="navigation-table__sort-header">
                <?php foreach ($variablesTV->getValue('navigationSorting') as $category): ?>
                    <div class="navigation-table__category" data-sort_name="<?= $category['sort_name'] ?>"
                        <?php if ($category['is_selected']): ?>
                            data-sort_type="<?= $category['sort_type'] ?>"
                        <?php endif; ?>>
                        <span class="navigation-table__sort-name"><?= $category['description'] ?></span>
                        <?php if ($category['is_selected']): ?>
                            <?php if ($category['sort_type'] == 'ASC'): ?>
                                <i class="navigation-table__sort-icon fas fa-caret-up"></i>
                            <?php else: ?>
                                <i class="navigation-table__sort-icon fas fa-caret-down"></i>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
