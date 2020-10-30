<?php $_VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="view-actions">

    <div class="view-actions__card">
        <div class="view-actions__header">Режимы просмотра</div>
        <?php foreach($_VT->getValue('available_view_modes') as $mode): ?>
            <a href="<?= $mode['ref'] ?>" class="view-actions__action" data-is_selected="<?= $mode['isSelected'] ?>">
                <div class="view-actions__label"><?= $mode['label'] ?></div>
            </a>
        <?php endforeach; ?>
    </div>