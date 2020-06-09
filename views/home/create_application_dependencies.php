<?php $variablesTV = VariableTransfer::getInstance(); ?>

<!-- Зависимость "Предмет экспертизы" от "Цель обращения" -->
<div class="radio__content-change-logic">
    <input data-when_change="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>"
           data-target_change="<?= _PROPERTY_IN_APPLICATION['expertise_subjects'] ?>"
           type="hidden"
           value='<?= $variablesTV->getValue('expertiseSubjects') ?>'>
</div>

<!-- Зависимость "Вид работ" от "Цель обращения" -->
<div class="modal__content-change-logic">
    <input data-when_change="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>"
           data-target_change="<?= _PROPERTY_IN_APPLICATION['type_of_work'] ?>"
           type="hidden"
           value='<?= $variablesTV->getValue('typeOfWorks') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Подотрасль" от "Функциональное назначение" -->
<div class="modal__content-change-logic">
   <input data-when_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose'] ?>"
          data-target_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose_subsector'] ?>"
          type="hidden"
          value='<?= $variablesTV->getValue('functionalPurposeSubsectors') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Группа" от "Функциональное назначение. Подотрасль" -->
<div class="modal__content-change-logic">
   <input data-when_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose_subsector'] ?>"
          data-target_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose_group'] ?>"
          type="hidden"
          value='<?= $variablesTV->getValue('functionalPurposeGroups') ?>'>
</div>

<!-- Зависимость "Федеральный проект" от "Национальный проект" -->
<div class="modal__content-change-logic">
   <input data-when_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose_subsector'] ?>"
          data-target_change="<?= _PROPERTY_IN_APPLICATION['functional_purpose_group'] ?>"
          type="hidden"
          value='<?= $variablesTV->getValue('federalProjects') ?>'>
</div>


<div>
    <input class="row-dependencies" type="hidden" value='<?= $variablesTV->getValue('displayDependencies') ?>'>
</div>

