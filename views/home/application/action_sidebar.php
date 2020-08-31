<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="sidebar-actions">
    <?php foreach($variablesTV->getValue('availableActions') as ['ref' => $ref, 'label' => $label]): ?>
        <a href="<?= $ref ?>" class="sidebar-actions__section">
            <div class="sidebar-actions__item"><?= $label ?></div>
        </a>
    <?php endforeach; ?>
</div>
</div>