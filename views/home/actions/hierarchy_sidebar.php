
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
    
    <div class="sidebar-hierarchy">
        
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-depth="0">
                <span class="sidebar-hierarchy__name">Заявление</span>
            </div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-depth="1">
                <span class="sidebar-hierarchy__name">Договор</span>
            </div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-depth="2">
                <span class="sidebar-hierarchy__name">Счет</span>
                <span class="sidebar-hierarchy__text">Процент оплаты - 100%</span>
            </div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-depth="0">
                <span class="sidebar-hierarchy__name">Сводное заключение</span>
            </div>
        </div>
        <div class="sidebar-hierarchy__section">
            <div class="sidebar-hierarchy__item" data-depth="1">
                <span class="sidebar-hierarchy__name">Какой-то раздел</span>
            </div>
        </div>
    </div>
