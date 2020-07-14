<?php $variablesTV = VariableTransfer::getInstance(); ?>

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
      <div class="header__user"><?= $variablesTV->getValue('userFIO') ?></div>
      <div class="header__links">
          <i class="header__icon fas fa-question-circle"></i>
          <i class="header__icon fas fa-sign-out-alt"></i>
      </div>
   </header>
