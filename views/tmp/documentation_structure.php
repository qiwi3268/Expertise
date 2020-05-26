


<?php foreach($structure1TV as $node): ?>
    <p data-node-id="<?= $node['id'] ?>" style="margin-left: <?= $node['depth']*35 ?>px"><?= $node['name'] ?></p>
<?php endforeach; ?>
