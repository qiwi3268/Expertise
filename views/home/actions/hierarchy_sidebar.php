
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>


<div class="application-form__header header-form">
    <div class="header-form__title">Заявление на экспертизу <?= $variablesTV->getValue('numerical_name') ?></div>
</div>

<input type="hidden" value="<?= $variablesTV->getValue('id_application') ?>" name="id_application">

<div class="application-form__body">
    <!--<div class="sidebar-hierarchy">
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-level="0">
                <i class="sidebar-hierarchy__icon fas fa-caret-right"></i>
                <div>Заявление</div>
            </div>
            <div class="sidebar-hierarchy__item" data-level="1">Договор</div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-level="0">Сводное заключение</div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-level="0">Заявление</div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-level="0">Заявление</div>
        </div>
    </div>-->
    
    <div class="view__sidebar">
    
    
        <div class="view__items">
            <div class="view__item">
                <i class="view__icon fas fa-file-alt"></i>
                <div class="view__info">
                    <span class="view__label">Стадия</span>
                    <span class="view__text">Формирование сводного замечания</span>
                </div>
            </div>
            <div class="view__item">
                <i class="view__icon fas fa-calendar-week"></i>
                <div class="view__info">
                    <span class="view__label">Контрольный срок заявления</span>
                    <span class="view__text">25.10.2020</span>
                </div>
            </div>
            <div class="view__item">
                <i class="view__icon fas fa-calendar-day"></i>
                <div class="view__info">
                    <span class="view__label">Контрольный срок стадии</span>
                    <span class="view__text">20.09.2020</span>
                </div>
            </div>
            <div class="view__item">
                <i class="view__icon fas fa-user-edit"></i>
                <div class="view__info">
                    <span class="view__label">Ответственные</span>
                    <span class="view__text">Заявитель</span>
                </div>
            </div>
        </div>
        
        <div class="sidebar-hierarchy">
            <?php foreach ($variablesTV->getValue('availableDocuments') as $document): ?>
                <div class="sidebar-hierarchy__section">
                    <a href="<?= $document['ref'] ?>" class="sidebar-hierarchy__item" data-depth="<?= $document['depth'] ?>">
                        <span class="sidebar-hierarchy__name"><?= $document['label'] ?></span>
                        <?php foreach ($document['descriptions'] as $description): ?>
                            <span class="sidebar-hierarchy__text"><?= $description ?></span>
                        <?php endforeach; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>