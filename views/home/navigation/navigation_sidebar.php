<?php $variablesTV = VariableTransfer::getInstance(); ?>

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


