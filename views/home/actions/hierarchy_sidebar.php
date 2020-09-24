
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
            <div class="info__item">
                <i class="info__icon fas fa-user-edit"></i>
                <div class="info__description">
                    <span class="info__label">Ответственные</span>
                    <span class="info__text">Заявитель</span>
                </div>
            </div>
        </div>

        <div class="view__hierarchy hierarchy">
            <?php foreach ($variablesTV->getValue('availableDocuments') as $document): ?>
                <div class="hierarchy__section">
                    <a href="<?= $document['ref'] ?>" class="hierarchy__item" data-depth="<?= $document['depth'] ?>">
                        <span class="hierarchy__name"><?= $document['label'] ?></span>
                        <?php foreach ($document['descriptions'] as $description): ?>
                            <span class="hierarchy__text"><?= $description ?></span>
                        <?php endforeach; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>





