<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<style>
    .block{
        border: #1c3959;
        color: white;
        margin-bottom: 10px;
    }
    .block__header{
        background: #8e8e8e;
    }
    .block__section{
        display: flex;
        flex-direction: row;
        background: #87c596;
    }
    .block__section_selected{
        display: flex;
        flex-direction: row;
        background-color: #db5151;
    }
    .section__label{
        text-align: center;
    }
    .section__counter{
        font-weight: bold;
    }
    
    
</style>

<?php foreach ($variablesTV->getValue('navigationBlocks') as $block): ?>
        
    <div class="block">
        <div class="block__header"><?= $block['label'] ?></div>
        
        <?php foreach ($block['sections'] as $section): ?>
            
            <?php if ($section['is_selected']): ?>
                <div class="block__section_selected">
            <?php else: ?>
                <div class="block__section">
            <?php endif; ?>
            
                    <div class="section__label">
                        <a href="<?= $section['ref'] ?>"><?= $section['label'] ?></a>
                    </div>
            
                    <?php if ($section['counter'] !== false): ?>
                        <div class="section__counter">
                            <?= $section['counter'] ?>
                        </div>
                    <?php endif; ?>
                </div>
        <?php endforeach; ?>
    </div>
    
<?php endforeach; ?>