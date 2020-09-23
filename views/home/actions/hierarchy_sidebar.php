
<?php $variablesTV = \Lib\Singles\VariableTransfer::getInstance(); ?>

<input type="hidden" value="<?= $variablesTV->getValue('id_application') ?>" name="id_application">

<div class="view__object">"Капитальный ремонт по замене системы АПС"в Муниципальном общеобразовательном учреждении «Школа-интернат для детей-сирот и детей, оставшихся без попечения родителей «Семья» г. Магнитогорска, расположенного по адресу: 455026, Челябинская область, г. Магнитогорск, ул. Дружбы, 25»</div>

<div class="view__header">
    <div class="view__title">Заявление на экспертизу <?= $variablesTV->getValue('numerical_name') ?></div>
</div>

<div class="view__container">
    
    <div class="view__sidebar">
        <div class="view__info info">
            <div class="info__item">
                <i class="info__icon fas fa-file-alt"></i>
                <div class="info__description">
                    <span class="info__label">Стадия</span>
                    <span class="info__text">Формирование сводного замечания</span>
                </div>
            </div>
            <div class="info__item">
                <i class="view__icon fas fa-calendar-week"></i>
                <div class="view__info">
                    <span class="view__label">Контрольный срок заявления</span>
                    <span class="view__text">25.10.2020</span>
                </div>
            </div>
            <div class="info__item">
                <i class="view__icon fas fa-calendar-day"></i>
                <div class="view__info">
                    <span class="view__label">Контрольный срок стадии</span>
                    <span class="view__text">20.09.2020</span>
                </div>
            </div>
            <div class="info__item">
                <i class="view__icon fas fa-user-edit"></i>
                <div class="view__info">
                    <span class="view__label">Ответственные</span>
                    <span class="view__text">Заявитель</span>
                </div>
            </div>
        </div>
        
        <div class="view__hierarchy hierarchy">
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
    
    <div class="view__body">
    
    </div>
    
    <div class="view__actions">
    
    
    </div>
    
</div>


<div class="application-form__body">

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