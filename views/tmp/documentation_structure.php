
<div class="documentation">

    <div class="documentation__node" data-node-id="1234" data-depth="0">
        <div class="documentation__header">
            <span class="documentation__name">Раздел с файлами</span>
            <i class="documentation__icon fas fa-plus"></i>
        </div>

        <div class="documentation__files files filled">
            <div class="files__item" data-id="381">
                <div class="files__info">
                    <i class="files__icon fas fa-file-word"></i>
                    <div class="files__name">Экспертное сопровождение_финальный тест (1).docx</div>
                </div>
                <div class="files__actions">
                    <i class="files__unload fas fa-file-download"></i>
                    <i class="files__delete fas fa-trash"></i>
                </div>
            </div>
            <div class="files__item" data-id="385">
                <div class="files__info">
                    <i class="files__icon fas fa-file-word"></i>
                    <div class="files__name">FullDocumentTemplate.docx</div>
                </div>
                <div class="files__actions">
                    <i class="files__unload fas fa-file-download"></i>
                    <i class="files__delete fas fa-trash"></i>
                </div>
            </div>
        </div>
    </div>



<?php foreach($structure1TV as $node): ?>
    <div class="documentation__node" data-node-id="<?= $node['id'] ?>" data-depth="<?= $node['depth'] ?>">
        <div class="documentation__header">
            <span class="documentation__name" data-depth="<?= $node['depth'] ?>"><?= $node['name'] ?></span>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php foreach($structure1TV as $node): ?>
    <p data-node-id="<?= $node['id'] ?>" style="margin-left: <?= $node['depth']*35 ?>px"><?= $node['name'] ?></p>
<?php endforeach; ?>
