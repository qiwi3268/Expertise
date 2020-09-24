<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>
<?php if ($variablesTV->getExistenceFlag('navigationData')): ?>
    <div class="table-navigation__footer">
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
    </div>
<?php endif; ?>

</div>
</div>

<input name="navigation__view-name" type="hidden" value="<?= $variablesTV->getValue('viewName') ?>">
</div>
<div class="empty-block"></div>