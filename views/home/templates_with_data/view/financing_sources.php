<?php
    $_data = \Lib\Singles\TemplateMaker::getSelfData();
    $_financingSources = $_data['financing_sources'];
?>

<?php var_dump($_data); ?>

<?php if (!empty($_financingSources['type_1'])): ?>
    <?php foreach ($_financingSources['type_1'] as $source): ?>
        <div class="view-table card">
            <div class="view-table__header card-expand">
                <div class="view-table__title">Бюджетные средства</div>
                <i class="view-table__icon fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="view-table__body card-body">
                <div class="view-table__row">
                    <div class="view-table__label">Уровень бюджета</div>
                    <div class="view-table__value"><?= !empty($source['budget_level']) ? $source['budget_level']['name'] : 'Не выбрано';  ?></div>
                </div>
                <?php if ($source['no_data']): ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Размер финансирования</div>
                        <div class="view-table__value">Нет данных</div>
                    </div>
                <?php else: ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Процент финансирования</div>
                        <div class="view-table__value"><?= $source['percent'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_2'])): ?>
    <?php foreach ($_financingSources['type_2'] as $source): ?>
        <div class="view-table card">
            <div class="view-table__header card-expand">
                <div class="view-table__title">Средства юридических лиц, указанных в ч. 2 статьи 48.2 ГрК</div>
                <i class="view-table__icon fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="view-table__body card-body">
                <div class="view-table__row">
                    <div class="view-table__label">Полное наименование</div>
                    <div class="view-table__value"><?= $source['full_name'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">ИНН</div>
                    <div class="view-table__value"><?= $source['INN'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">КПП</div>
                    <div class="view-table__value"><?= $source['KPP'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">ОГРН</div>
                    <div class="view-table__value"><?= $source['OGRN'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">Адрес</div>
                    <div class="view-table__value"><?= $source['address'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">Место нахождения</div>
                    <div class="view-table__value"><?= $source['location'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">Телефон</div>
                    <div class="view-table__value"><?= $source['telephone'] ?></div>
                </div>
                <div class="view-table__row">
                    <div class="view-table__label">Адрес электронной почты</div>
                    <div class="view-table__value"><?= $source['email'] ?></div>
                </div>
                <?php if ($source['no_data']): ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Размер финансирования</div>
                        <div class="view-table__value">Нет данных</div>
                    </div>
                <?php else: ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Процент финансирования</div>
                        <div class="view-table__value"><?= $source['percent'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_3'])): ?>
    <?php foreach ($_financingSources['type_3'] as $source): ?>
        <div class="view-table card">
            <div class="view-table__header card-expand">
                <div class="view-table__title">Собственные средства застройщика</div>
                <i class="view-table__icon fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="view-table__body card-body">
                <?php if ($source['no_data']): ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Размер финансирования</div>
                        <div class="view-table__value">Нет данных</div>
                    </div>
                <?php else: ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Процент финансирования</div>
                        <div class="view-table__value"><?= $source['percent'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($_financingSources['type_4'])): ?>
    <?php foreach ($_financingSources['type_4'] as $source): ?>
        <div class="view-table card">
            <div class="view-table__header card-expand">
                <div class="view-table__title">Средства инвестора</div>
                <i class="view-table__icon fas fa-chevron-down arrow-down card-icon"></i>
            </div>
            <div class="view-table__body card-body">
                <?php if ($source['no_data']): ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Размер финансирования</div>
                        <div class="view-table__value">Нет данных</div>
                    </div>
                <?php else: ?>
                    <div class="view-table__row">
                        <div class="view-table__label">Процент финансирования</div>
                        <div class="view-table__value"><?= $source['percent'] ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
