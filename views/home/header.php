<?php $VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<div class="main-container">
    <header>
        <div class="header__logo">
            <img src="/views/img/tmp/logo.png" alt="">
            <div class="header__logo-text">
                <span>Госэкспертиза</span>
                <div class="header__subtitle">
                    <span>Челябинской области</span>
                </div>
            </div>
        </div>
        <a href="/home/navigation" class="header__home">
            <i class="header__icon fas fa-home"></i>
        </a>
        <div class="header__user"><?= $VT->getValue('user_FIO') ?></div>
        <div class="header__links">
            <i class="header__icon fas fa-question-circle"></i>
            <a href="/" class="header__out">
                <i class="header__icon fas fa-sign-out-alt"></i>
            </a>
        </div>
    </header>
