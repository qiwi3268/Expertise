
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>


<div class="view__object">"Капитальный ремонт по замене системы АПС"в Муниципальном общеобразовательном учреждении «Школа-интернат для детей-сирот и детей, оставшихся без попечения родителей «Семья» г. Магнитогорска, расположенного по адресу: 455026, Челябинская область, г. Магнитогорск, ул. Дружбы, 25»</div>

<div class="view__header">
    <div class="view__title">Заявление на экспертизу 123</div>
</div>

<div class="view__container">

    <div class="view__sidebar">
        <div class="view__info info">
            <div class="info__item">
                <i class="info__icon fas fa-calendar-week"></i>
                <div class="info__description">
                    <span class="info__label">Контрольный срок заявления</span>
                    <span class="info__text">25.10.2020</span>
                </div>
            </div>
            <div class="info__item">
                <i class="info__icon fas fa-calendar-day"></i>
                <div class="info__description">
                    <span class="info__label">Контрольный срок стадии</span>
                    <span class="info__text">20.09.2020</span>
                </div>
            </div>
            <?php if ($variablesTV->getExistenceFlag('responsible')): ?>
                <div class="info__item" data-tooltip>
                    <i class="info__icon fas fa-user-edit"></i>
                    <div class="info__description">
                        <span class="info__label">Ответственные</span>
                        <span class="info__text"><?= $variablesTV->getValue('responsibleLabel') ?></span>
                    </div>
                    
                    <div class="responsible" data-tooltip_content hidden>
                        <?php foreach ($variablesTV->getValue('responsibleUsers') as $FIO): ?>
                            <span class="responsible__name"><?= $FIO ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="view__hierarchy hierarchy">
            <?php if (isset($variablesTV->getValue('availableDocuments')['application'][0])): ?>
                <?php $_application = $variablesTV->getValue('availableDocuments')['application'][0] ?>
                <div class="hierarchy__section" data-selected="<?= $_application['isSelected'] ?>">
                    <a href="<?= $_application['ref'] ?>" class="hierarchy__item" data-depth="0">
                        <span class="hierarchy__name"><?= $_application['label'] ?></span>
                        <div class="hierarchy__description">
                            <span class="hierarchy__stage"><?= $_application['stage'] ?></span>
                            <?php foreach ($_application['descriptions'] as $description): ?>
                                <span class="hierarchy__descriptions"><?= $description ?></span>
                            <?php endforeach; ?>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (isset($variablesTV->getValue('availableDocuments')['total_cc'][0])): ?>
                <?php $_total_cc = $variablesTV->getValue('availableDocuments')['total_cc'][0] ?>
                <div class="hierarchy__section" data-selected="<?= $_total_cc['isSelected'] ?>">
                    <a href="<?= $_total_cc['ref'] ?>" class="hierarchy__item" data-depth="1">
                        <span class="hierarchy__name"><?= $_total_cc['label'] ?></span>
                        <div class="hierarchy__description">
                            <span class="hierarchy__stage"><?= $_total_cc['stage'] ?></span>
                            <?php foreach ($_total_cc['descriptions'] as $description): ?>
                                <span class="hierarchy__descriptions"><?= $description ?></span>
                            <?php endforeach; ?>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (isset($variablesTV->getValue('availableDocuments')['sections'])): ?>
                <?php $_sections = $variablesTV->getValue('availableDocuments')['sections'] ?>
                <?php foreach ($_sections as $section): ?>
                    <div class="hierarchy__section" data-selected="<?= $section['isSelected'] ?>" data-tooltip>
                        <a href="<?= $section['ref'] ?>" class="hierarchy__item" data-depth="2">
                            <span class="hierarchy__name"><?= $section['label'] ?></span>
                            <div class="hierarchy__description">
                                <span class="hierarchy__stage"><?= $section['stage'] ?></span>
                                <span class="hierarchy__experts">
                                    <i class="hierarchy__experts-icon fas fa-user-friends"></i>
                                    <span class="hierarchy__experts-title">Назначенные эксперты:</span>
                                </span>
                                <?php if (!empty($section['descriptions'])): ?>
                                    <?php foreach ($section['descriptions'] as $description): ?>
                                        <span class="hierarchy__descriptions"><?= $description ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="hierarchy__info" data-tooltip_content hidden>
                            <div class="hierarchy__tooltip"><?= $section['tooltip'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>





