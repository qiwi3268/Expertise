<div class="application-form__header header-form">
   <div class="header-form__title"><?= $appNumNameTV ?></div>
   <div class="header-form__actions">
      <div id="application_save" class="header-form__btn">
         <span class="header-form__text">Сохранить</span>
         <i class="fas fa-save header-form__icon"></i>
      </div>
      <div class="header-form__btn">
         <span class="header-form__text">Удалить</span>
         <i class="fas fa-trash header-form__icon"></i>
      </div>
   </div>
</div>
<div class="application-form__body">
   <div class="application-form__sidebar sidebar-form">
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Сведения о проекте и цели заявления</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning" data-card="purpose">
         <span class="sidebar-form__text">Сведения о цели обращения</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning" data-card="object">
         <span class="sidebar-form__text">Сведения об объекте</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning" data-card="applicant">
         <span class="sidebar-form__text">Сведения о заявителе</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Застройщик(заказчик по договору)</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Сведения об исполнителях работ</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Плательщик</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Условия предоставления услуги</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
      <div class="sidebar-form__row warning">
         <span class="sidebar-form__text">Сведения об исполнителях работ</span>
         <i class="sidebar-form__icon fas fa-exclamation warning"></i>
      </div>
   </div>
   <form id="application" class="application-form__cards required" action="/tmp/create_application" method="POST">
      <input type="hidden" value="<?= $applicationId ?>" name="<?= _PROPERTY_IN_APPLICATION['application_id'] ?>">

      <div class="application-form__card card-form" data-type="purpose">
         <div class="card-form__header">
                <span class="card-form__title">
                    СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ
                </span>
            <i class="fas fa-chevron-down card-form__icon arrow-down"></i>
         </div>
         <div class="card-form__body body-card">
            <!--Цель обращения-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>" data-required="true">
               <span class="body-card__title required">Цель обращения</span>

               <div class="body-card__field">
                  <div class="body-card__select modal-select">
                     <span class="body-card__value">Выберите значение</span>
                     <i class="body-card__icon fas fa-bars"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>

               <div class="modal">
                  <i class="modal__close fas fa-times"></i>
                  <div class="modal__items">
                     <?php foreach($expertisePurposesTV as $pageNumber => $page): ?>
                        <div class="modal__page" data-page="<?= $pageNumber ?>">
                           <?php foreach($page as $item): ?>
                              <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                           <?php endforeach; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>">
            </div>
            <!--Цель обращения-->

            <!--Предмет эекспертизы-->
            <div class="body-card__row center" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_subject'] ?>" data-required="true">
               <span class="body-card__title required">Предмет экспертизы</span>
               <div class="body-card__field radio" data-multiple="true">
                  <div class="radio__body">
                     <span class="radio__title">Выберите цель обращения</span>
                  </div>
               </div>
               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['expertise_subject'] ?>">
            </div>
            <!--Предмет эекспертизы-->

            <!--Дополнительная информация-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['additional_information'] ?>" data-required="true">
               <span class="body-card__title required">Дополнительная информация</span>
               <div class="body-card__field">
                  <textarea class="body-card__input application-input" name="<?= _PROPERTY_IN_APPLICATION['additional_information'] ?>"></textarea>
               </div>
            </div>
            <!--Дополнительная информация-->
         </div>
      </div>

      <div class="application-form__card card-form" data-type="object">
         <div class="card-form__header">
                <span class="card-form__title">
                    СВЕДЕНИЯ ОБ ОБЪЕКТЕ
                </span>
            <i class="fas fa-chevron-down card-form__icon arrow-down"></i>
         </div>
         <div class="card-form__body body-card">
            <!--Наименование объекта-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['object_name'] ?>" data-required="true">
               <span class="body-card__title required">Наименование объекта</span>
               <div class="body-card__field">
                  <textarea class="body-card__input application-input" name="<?= _PROPERTY_IN_APPLICATION['object_name'] ?>"></textarea>
               </div>
            </div>
            <!--Наименование объекта-->

            <!--Вид объекта-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['type_of_object'] ?>" data-required="true">
               <span class="body-card__title required">Вид объекта</span>
               <div class="body-card__field">
                  <div class="body-card__select modal-select">
                     <span class="body-card__value">Выберите значение</span>
                     <i class="body-card__icon fas fa-bars"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>
               <div class="modal">
                  <i class="modal__close fas fa-times"></i>
                  <div class="modal__items">
                     <?php foreach($typeOfObjectsTV as $pageNumber => $page): ?>
                        <div class="modal__page" data-page="<?= $pageNumber ?>">
                           <?php foreach($page as $item): ?>
                              <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                           <?php endforeach; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['type_of_object'] ?>">
            </div>
            <!--Вид объекта-->

            <!--Функциональное назначение-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['functional_purpose'] ?>" data-required="true">
               <span class="body-card__title required">Функциональное назначение</span>

               <div class="body-card__field">
                  <div class="body-card__select modal-select">
                     <span class="body-card__value">Выберите значение</span>
                     <i class="body-card__icon fas fa-bars"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>
               <div class="modal">
                  <i class="modal__close fas fa-times"></i>
                  <div class="modal__items">
                     <?php foreach($functionalPurposesTV as $pageNumber => $page): ?>
                        <div class="modal__page" data-page="<?= $pageNumber ?>">
                           <?php foreach($page as $item): ?>
                              <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                           <?php endforeach; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['functional_purpose'] ?>">
            </div>
            <!--Функциональное назначение-->

            <!--===============Линейные объекты капитального строительства===============-->
            <!--Номер утверждения документации по планировке территории-->
            <div class="body-card__row" data-inactive="true" data-row_name="<?= _PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] ?>" data-pattern="number">
               <span class="body-card__title">Номер утверждения документации по планировке территории</span>
               <div class="body-card__field">
                  <input class="body-card__input body-card__result application-input" type="text" name="<?= _PROPERTY_IN_APPLICATION['number_planning_documentation_approval'] ?>" placeholder="Введите значение">
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>
            </div>
            <!--Номер утверждения документации по планировке территории-->
            <!--Дата утверждения документации по планировке территории-->
            <div class="body-card__row" data-inactive="true" data-row_name="<?= _PROPERTY_IN_APPLICATION['date_planning_documentation_approval'] ?>">
               <span class="body-card__title">Дата утверждения документации по планировке территории</span>

               <div class="body-card__field">
                  <div class="body-card__select modal-calendar">
                     <span class="body-card__value">Выберите дату</span>
                     <i class="body-card__icon fas fa-calendar-alt"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>

               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['date_planning_documentation_approval'] ?>">
            </div>
            <!--Дата утверждения документации по планировке территории-->
            <!--===============Линейные объекты капитального строительства===============-->

            <!--==========Производственные/непроизводственные объекты капитального строительства==========-->
            <!--Номер ГПЗУ-->
            <div class="body-card__row" data-inactive="true" data-row_name="<?= _PROPERTY_IN_APPLICATION['number_GPZU'] ?>" data-pattern="number">
               <span class="body-card__title">Номер ГПЗУ</span>
               <div class="body-card__field">
                  <input class="body-card__input body-card__result application-input" type="text" name="<?= _PROPERTY_IN_APPLICATION['number_GPZU'] ?>" placeholder="Введите значение">
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>
            </div>
            <!--Номер ГПЗУ-->
            <!--Дата ГПЗУ-->
            <div class="body-card__row" data-inactive="true" data-row_name="<?= _PROPERTY_IN_APPLICATION['date_GPZU'] ?>">
               <span class="body-card__title">Дата ГПЗУ</span>

               <div class="body-card__field">
                  <div class="body-card__select modal-calendar">
                     <span class="body-card__value">Выберите дату</span>
                     <i class="body-card__icon fas fa-calendar-alt"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>

               <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['date_GPZU'] ?>">
            </div>
            <!--Дата ГПЗУ-->
            <!--==========Производственные/непроизводственные объекты капитального строительства==========-->

            <!--Вид работ-->
            <div class="body-card__row" data-row_name="type_of_work" data-required="true">
               <span class="body-card__title required">Вид работ</span>

               <div class="body-card__field">
                  <div class="body-card__select modal-select">
                     <span class="body-card__value">Выберите значение</span>
                     <i class="body-card__icon fas fa-bars"></i>
                  </div>
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>

               <div class="modal">
                  <i class="modal__close fas fa-times"></i>
                  <div class="modal__items"></div>
               </div>
               <input class="body-card__result" type="hidden" name="type_of_work">
            </div>
            <!--Вид работ-->

            <!--Кадастровый номер земельного участка-->
            <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['cadastral_number'] ?>" data-pattern="number">
               <span class="body-card__title">Кадастровый номер земельного участка</span>
               <div class="body-card__field">
                  <input class="body-card__input body-card__result application-input" type="text" name="<?= _PROPERTY_IN_APPLICATION['cadastral_number'] ?>" placeholder="Введите значение">
                  <span class="body-card__error">Поле обязательно для заполнения</span>
               </div>
            </div>
            <!--Кадастровый номер земельного участка-->



         </div>
      </div>


   </form>
</div>