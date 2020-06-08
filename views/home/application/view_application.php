<div class="application-form__header header-form">
   <div class="header-form__title">Заявление</div>
</div>





<div class="application-form__body">
   <div class="sidebar-hierarchy">
      <div class="sidebar-hierarchy__section">
         <div class="sidebar-hierarchy__item" data-level="0">Заявление</div>
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
   </div>
   
   
   
   <div class="application-form__cards required">
      <div class="application-form__card card-form" data-type="purpose">
         <div class="card-form__header">
            <?php if($variablesTV->getValue('block1_completed')): ?>
               <i class="card-form__icon-state fas fa-check-circle valid"></i>
            <?php else: ?>
               <i class="card-form__icon-state fas fa-exclamation-circle"></i>
            <?php endif; ?>
            <span class="card-form__title">СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ</span>
            <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
         </div>
         
         <div class="card-form__body body-card">
            <!--Цель обращения-->
            <div class="body-card__row">
               <div class="body-card__title">
<!--                  <i class="body-card__icon fas fa-exclamation-circle"></i>-->
                  <span class="body-card__title-text">Цель обращения</span>
               </div>
               <span class="body-card__value">
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_purpose'])): ?>
                      <?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['expertise_purpose']) ?>
                  <?php else: ?>
                     <i class="body-card__icon fas fa-exclamation-circle"></i>
                     <span class="body-card__text">Не выбрана</span>
                  <?php endif; ?>
               </span>
            </div>
            <!--Цель обращения-->
   
            <!--Предмет экспертизы-->
            <div class="body-card__row center">
               <span class="body-card__title">Предмет экспертизы</span>
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['expertise_subjects'])): ?>
                     <div class="body-card__field radio">
                        <div class="radio__body">
                           <?php foreach($variablesTV->getValue(_PROPERTY_IN_APPLICATION['expertise_subjects']) as $subject): ?>
                              <div class="radio__item">
                                 <i class="radio__icon fas fa-check"></i>
                                 <span class="radio__value"><?= $subject ?></span>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                  <?php else: ?>
                     <div class="body-card__value">
                        <i class="body-card__icon fas fa-exclamation-circle"></i>
                        <span class="body-card__text">Не выбран</span>
                     </div>
                  <?php endif; ?>
            </div>
            <!--Предмет эекспертизы-->
            
            <!--Дополнительная информация-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['additional_information'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Дополнительная информация</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['additional_information']) ?></span>
               </div>
            <?php endif; ?>
            <!--Дополнительная информация-->
         </div>
      
      </div>
      
      <div class="application-form__card card-form" data-type="object">
         <div class="card-form__header">
            <?php if($variablesTV->getValue('block2_completed')): ?>
               <i class="card-form__icon-state fas fa-check-circle valid"></i>
            <?php else: ?>
               <i class="card-form__icon-state fas fa-exclamation-circle"></i>
            <?php endif; ?>
            <span class="card-form__title">СВЕДЕНИЯ ОБ ОБЪЕКТЕ</span>
            <i class="card-form__icon-expand fas fa-chevron-down arrow-down"></i>
         </div>
         <div class="card-form__body body-card">
            <!--Наименование объекта-->
            <div class="body-card__row">
               <span class="body-card__title">Наименование объекта</span>
               <span class="body-card__value">
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['object_name'])): ?>
                     <?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['object_name']) ?>
                  <?php else: ?>
                     <i class="body-card__icon fas fa-exclamation-circle"></i>
                     <span class="body-card__text">Не указано</span>
                  <?php endif; ?>
               </span>
            </div>
            <!--Наименование объекта-->
            
            <!--Вид объекта-->
             <div class="body-card__row">
                 <div class="body-card__title">
                     <span class="body-card__title-text">Вид объекта</span>
                 </div>
                 <span class="body-card__value">
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_object'])): ?>
                      <?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['type_of_object']) ?>
                  <?php else: ?>
                      <i class="body-card__icon fas fa-exclamation-circle"></i>
                      <span class="body-card__text">Не выбран</span>
                  <?php endif; ?>
               </span>
             </div>
            <!--Вид объекта-->
            
            <!--Функциональное назначение-->
            <div class="body-card__row">
               <span class="body-card__title">Функциональное назначение</span>
               <span class="body-card__value">
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['functional_purpose'])): ?>
                     <?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['functional_purpose']) ?>
                  <?php else: ?>
                     <i class="body-card__icon fas fa-exclamation-circle"></i>
                     <span class="body-card__text">Не выбрано</span>
                  <?php endif; ?>
               </span>
            </div>
            <!--Функциональное назначение-->
            
            <!--==========Производственные/непроизводственные объекты капитального строительства==========-->
            <!--Номер утверждения документации по планировке территории-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['number_planning_documentation_approval'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Номер утверждения документации по планировке территории</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['number_planning_documentation_approval']) ?></span>
               </div>
            <?php endif; ?>

            <!--Дата утверждения документации по планировке территории-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Дата утверждения документации по планировке территории</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['date_planning_documentation_approval']) ?></span>
               </div>
            <?php endif; ?>

            <!--===============Линейные объекты капитального строительства===============-->
            <!--Номер ГПЗУ-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['number_GPZU'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Номер ГПЗУ</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['number_GPZU']) ?></span>
               </div>
            <?php endif; ?>


            <!--Дата ГПЗУ-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['date_GPZU'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Дата ГПЗУ</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['date_GPZU']) ?></span>
               </div>
            <?php endif; ?>


            <!--Вид работ-->
            <div class="body-card__row">
               <span class="body-card__title">Вид работ</span>
               <span class="body-card__value">
                  <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['type_of_work'])): ?>
                     <?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['type_of_work']) ?>
                  <?php else: ?>
                     <i class="body-card__icon fas fa-exclamation-circle"></i>
                     <span class="body-card__text">Не выбран</span>
                  <?php endif; ?>
               </span>
            </div>

            
            <!--Кадастровый номер земельного участка-->
            <?php if($variablesTV->getExistenceFlag(_PROPERTY_IN_APPLICATION['cadastral_number'])): ?>
               <div class="body-card__row">
                  <span class="body-card__title">Кадастровый номер земельного участка</span>
                  <span class="body-card__value"><?= $variablesTV->getValue(_PROPERTY_IN_APPLICATION['cadastral_number']) ?></span>
               </div>
            <?php endif; ?>


         </div>
      </div>
   </div>
   
   <div class="sidebar-actions">
      <div class="sidebar-actions__section">
         <div class="sidebar-actions__item" data-level="0">Редактировать</div>
      </div>
      <div class="sidebar-actions__section">
         <div class="sidebar-actions__item" data-level="0">Передать на экспертизу</div>
      </div>
      <div class="sidebar-actions__section">
         <div class="sidebar-actions__item" data-level="0">Редактировать</div>
      </div>
      <div class="sidebar-actions__section">
         <div class="sidebar-actions__item" data-level="0">Редактировать</div>
      </div>
   </div>
</div>