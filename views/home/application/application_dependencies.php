<?php $VT = \Lib\Singles\VariableTransfer::getInstance(); ?>

<!-- Зависимость "Предмет экспертизы" от "Цель обращения" -->
<div class="radio__content-change-logic">
    <input data-when_change="expertise_purpose"
           data-target_change="expertise_subjects"
           type="hidden"
           value='<?= $VT->getValue('expertise_subject') ?>'>
</div>

<!-- Зависимость "Вид работ" от "Цель обращения" -->
<div class="modal__content-change-logic">
    <input data-when_change="expertise_purpose"
           data-target_change="type_of_work"
           type="hidden"
           value='<?= $VT->getValue('type_of_work') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Подотрасль" от "Функциональное назначение" -->
<div class="modal__content-change-logic">
   <input data-when_change="functional_purpose"
          data-target_change="functional_purpose_subsector"
          type="hidden"
          value='<?= $VT->getValue('functional_purpose_subsector') ?>'>
</div>

<!-- Зависимость "Функциональное назначение. Группа" от "Функциональное назначение. Подотрасль" -->
<div class="modal__content-change-logic">
   <input data-when_change="functional_purpose_subsector"
          data-target_change="functional_purpose_group"
          type="hidden"
          value='<?= $VT->getValue('functional_purpose_group') ?>'>
</div>

<!-- Зависимость "Федеральный проект" от "Национальный проект" -->
<div class="modal__content-change-logic">
   <input data-when_change="national_project"
          data-target_change="federal_project"
          type="hidden"
          value='<?= $VT->getValue('federal_project') ?>'>
</div>

<?php
    $_dependencies = new \Classes\Application\DisplayDependenciesApplicationForm();
    $_blockDependencies = json_encode($_dependencies->getBlockDependencies());
    $_requireDependencies = json_encode($_dependencies->getRequireDependencies());
?>
<input id="block_dependencies" type="hidden" value='<?= $_blockDependencies ?>'>
<input id="require_dependencies" type="hidden" value='<?= $_requireDependencies ?>'>


