<?php $variablesTV = VariableTransfer::getInstance(); ?>

<!-- Зависимость "Предмет экспертизы" от "Цель обращения" -->
<div class="radio__content-change-logic">
    <input data-when_change="expertise_purpose"
           data-target_change="expertise_subjects"
           type="hidden"
           value='<?= $variablesTV->getValue('expertise_subjects') ?>'>
</div>

<!-- Зависимость "Вид работ" от "Цель обращения" -->
<div class="modal__content-change-logic">
    <input data-when_change="expertise_purpose"
           data-target_change="type_of_work"
           type="hidden"
           value='<?= $variablesTV->getValue('type_of_work') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Подотрасль" от "Функциональное назначение" -->
<div class="modal__content-change-logic">
   <input data-when_change="functional_purpose"
          data-target_change="functional_purpose_subsector"
          type="hidden"
          value='<?= $variablesTV->getValue('functional_purpose_subsector') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Группа" от "Функциональное назначение. Подотрасль" -->
<div class="modal__content-change-logic">
   <input data-when_change="functional_purpose_subsector"
          data-target_change="functional_purpose_group"
          type="hidden"
          value='<?= $variablesTV->getValue('functional_purpose_group') ?>'>
</div>

<!-- Зависимость "Федеральный проект" от "Национальный проект" -->
<div class="modal__content-change-logic">
   <input data-when_change="national_project"
          data-target_change="federal_project"
          type="hidden"
          value='<?= $variablesTV->getValue('federal_project') ?>'>
</div>


<div>
    <input class="row-dependencies" type="hidden" value='<?= $variablesTV->getValue('displayDependencies') ?>'>
    <input class="block-dependencies" type="hidden" value='<?= $variablesTV->getValue('blockDependencies') ?>'>
</div>

