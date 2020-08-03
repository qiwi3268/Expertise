<?php $variablesTV = VariableTransfer::getInstance(); ?>

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

<div class="main-container">
    <div class="navigation">

        <div class="navigation__sidebar sidebar-navigation">
            <?php foreach ($variablesTV->getValue('navigationBlocks') as $block): ?>
                <div class="sidebar-navigation__block">
                    <div class="sidebar-navigation__header"><?= $block['label'] ?></div>
                    <?php foreach ($block['sections'] as $section): ?>
                        <div class="sidebar-navigation__section" data-selected="<?= $section['is_selected'] ?>">
                            <a class="sidebar-navigation__label" href="<?= $section['ref'] ?>"><?= $section['label'] ?></a>
                            <?php if ($section['counter'] !== false): ?>
                                <div class="sidebar-navigation__counter">
                                    <?= $section['counter'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>


