


<div class="view__actions">

    <?php foreach($variablesTV->getValue('availableActions') as ['ref' => $ref, 'label' => $label]): ?>
        <a href="<?= $ref ?>" class="view__action">
            <div class="view__label"><?= $label ?></div>
        </a>
    <?php endforeach; ?>

</div>


</div>