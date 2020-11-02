<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

    
    <div class="view-actions__card">
        <div class="view-actions__header">Доступные действия</div>
        <?php foreach($_VT->getValue('available_actions') as ['ref' => $ref, 'label' => $label]): ?>
            <a href="<?= $ref ?>" class="view-actions__action">
                <div class="view-actions__label"><?= $label ?></div>
            </a>
        <?php endforeach; ?>
    </div>
    
<!--<div class="view-actions">-->
</div>

<!--<div class="view__container">-->
</div>