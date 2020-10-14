


<div class="view-actions">
    <div class="view-actions__header">Доступные действия</div>
    <?php foreach($variablesTV->getValue('availableActions') as ['ref' => $ref, 'label' => $label]): ?>
        <a href="<?= $ref ?>" class="view-actions__action">
            <div class="view-actions__label"><?= $label ?></div>
        </a>
    <?php endforeach; ?>

</div>


</div>