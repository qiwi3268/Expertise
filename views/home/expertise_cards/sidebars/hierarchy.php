
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>


<div class="view__object">Реконструкция системы теплоснабжения старой части г. Сатка, в том числе: реконструкция сетей теплоснабжения старой части г. Сатка, строительство газовой котельной с подводящими сетями</div>

<div class="view__header">
    <div class="view__title">Заявление на экспертизу 123</div>
</div>

<div class="view__container">

    <div class="view__sidebar">
        <div class="view__info info">
            <div class="info__item">
                <i class="info__icon fas fa-calendar-week"></i>
                <div class="info__description">
                    <span class="info__title">Контрольный срок заявления</span>
                    <span class="info__label">25.10.2020</span>
                </div>
            </div>
            <div class="info__item">
                <i class="info__icon fas fa-calendar-day"></i>
                <div class="info__description">
                    <span class="info__title">Контрольный срок стадии</span>
                    <span class="info__label">20.09.2020</span>
                </div>
            </div>
            <?php if ($variablesTV->getExistenceFlag('responsible')): ?>
                <div class="info__item" data-tooltip_container data-tooltip>
                    <i class="info__icon fas fa-user-edit"></i>
                    <div class="info__description">
                        <span class="info__title">Ответственные</span>
                        <span class="info__label"><?= $variablesTV->getValue('responsibleLabel') ?></span>
                    </div>

                    <div class="responsible" data-tooltip_content hidden>
                        <?php foreach ($variablesTV->getValue('responsibleUsers') as $FIO): ?>
                            <dive class="responsible__name"><?= $FIO ?></dive>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="view__hierarchy hierarchy">
            <?php if (isset($variablesTV->getValue('available_documents')['application'][0])): ?>
                <?php $_application = $variablesTV->getValue('available_documents')['application'][0] ?>
                <div class="hierarchy__section" data-selected="<?= $_application['isSelected'] ?>">
                    <a href="<?= $_application['ref'] ?>" class="hierarchy__item" data-depth="0">
                        <span class="hierarchy__name"><?= $_application['label'] ?></span>
                        <div class="hierarchy__description">
                            <?php $_info = $_application['info'] ?>
                            <span class="hierarchy__stage"><?= $_application['stage'] ?></span>
                        </div>

                    </a>
                </div>
            <?php endif; ?>
            <?php if (isset($variablesTV->getValue('available_documents')['total_cc'][0])): ?>
                <?php $_total_cc = $variablesTV->getValue('available_documents')['total_cc'][0] ?>
                <div class="hierarchy__section" data-selected="<?= $_total_cc['isSelected'] ?>">
                    <a href="<?= $_total_cc['ref'] ?>" class="hierarchy__item" data-depth="1">
                        <span class="hierarchy__name"><?= $_total_cc['label'] ?></span>
                        <?php $_info = $_total_cc['info'] ?>
                        <div class="hierarchy__description">
                            <span class="hierarchy__stage"><?= $_total_cc['stage'] ?></span>
                            <div class="hierarchy__card">
                                <span class="hierarchy__label">Ведущий</span>
                                <div class="hierarchy__experts">
                                    <span class="hierarchy__descriptions"><?= $_info['leadExpert'] ?></span>
                                </div>
                            </div>
                            <div class="hierarchy__card">
                                <span class="hierarchy__label">Общая часть</span>
                                <div class="hierarchy__experts">
                                    <?php foreach ($_info['commonPartExperts'] as $expert): ?>
                                        <span class="hierarchy__descriptions"><?= $expert ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    </a>
                </div>
            <?php endif; ?>
            <?php if (isset($variablesTV->getValue('available_documents')['sections'])): ?>
                <?php $_sections = $variablesTV->getValue('available_documents')['sections'] ?>
                <?php foreach ($_sections as $section): ?>
                    <div class="hierarchy__section" data-selected="<?= $section['isSelected'] ?>" data-tooltip_container data-tooltip>
                        <a href="<?= $section['ref'] ?>" class="hierarchy__item" data-depth="2">
                            <span class="hierarchy__name"><?= $section['label'] ?></span>
                            <div class="hierarchy__description">
                                <?php $_info = $section['info'] ?>
                                <span class="hierarchy__stage"><?= $section['stage'] ?></span>
                                <div class="hierarchy__card">
                                    <i class="hierarchy__label fas fa-users"></i>
                                    <div class="hierarchy__experts">
                                        <?php foreach ($_info['assignedExperts'] as $expert): ?>
                                            <span class="hierarchy__descriptions"><?= $expert ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="hierarchy__info" data-tooltip_content hidden>
                            <div class="hierarchy__tooltip"><?= $_info['tooltipName'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        
        
    </div>





