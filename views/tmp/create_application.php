<div class="radio__content-change-logic">
   <input data-when_change="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>" data-target_change="<?= _PROPERTY_IN_APPLICATION['expertise_subject'] ?>" type="hidden" value='<?= $expertiseSubjectsIH ?>'>
</div>
<div class="modal__content-change-logic">
   <input data-when_change="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>" data-target_change="type_of_work" type="hidden" value='<?= $typeOfWorksIH ?>'>
</div>

<div>
    <input class="row-dependencies" type="hidden" value='<?= $displayDependenciesIH ?>'>
</div>



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
      <div class="header__user"><?= $userFioTV ?></div>
      <div class="header__links">
         <i class="fas fa-question-circle header__icon"></i>
         <i class="fas fa-sign-out-alt header__icon"></i>
      </div>
   </header>
   <main class="application-form">
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
            <div class="sidebar-form__row">
               <span class="sidebar-form__text">Сведения о проекте и цели заявления</span>
            </div>
            <div class="sidebar-form__row" data-card="applicant">
               <span class="sidebar-form__text">Сведения о заявителе</span>
            </div>
            <div class="sidebar-form__row">
               <span class="sidebar-form__text">Застройщик(заказчик по договору)</span>
            </div>
            <div class="sidebar-form__row sidebar-form__row--valid">
               <span class="sidebar-form__text">Сведения об исполнителях работ</span>
               <i class="fas fa-check sidebar-form__icon"></i>
            </div>
            <div class="sidebar-form__row">
               <span class="sidebar-form__text">Плательщик</span>
            </div>
            <div class="sidebar-form__row sidebar-form__row--invalid">
               <span class="sidebar-form__text">Условия предоставления услуги</span>
               <i class="fas fa-times sidebar-form__icon"></i>
            </div>
            <div class="sidebar-form__row">
               <span class="sidebar-form__text">Сведения об исполнителях работ</span>
            </div>
         </div>
         <form id="application" class="application-form__cards required" action="/tmp/create_application" method="POST">
            <input type="hidden" value="<?= $applicationId ?>" name="<?= _PROPERTY_IN_APPLICATION['application_id'] ?>">

            <div class="application-form__card card-form">
               <div class="card-form__header">
                   <span class="card-form__title">
                       СВЕДЕНИЯ О ЦЕЛИ ОБРАЩЕНИЯ
                   </span>
                  <i class="fas fa-chevron-down card-form__icon arrow-down"></i>
               </div>
               <div class="card-form__body body-card">
                  <div class="body-card__row" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_purpose'] ?>" data-required="true">
                     <span class="body-card__title required">Цель обращения</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-select">
                           <span class="modal-select__value">Выберите значение</span>
                           <i class="modal-select__icon fas fa-bars"></i>
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

                  <div class="body-card__row center" data-row_name="<?= _PROPERTY_IN_APPLICATION['expertise_subject'] ?>" data-required="true">
                     <span class="body-card__title required">Предмет экспертизы</span>
                     <div class="body-card__field radio" data-multiple="true">
                        <div class="radio__body">
                           <span class="radio__title">Выберите цель обращения</span>
                        </div>
                     </div>
                     <input class="body-card__result" type="hidden" name="<?= _PROPERTY_IN_APPLICATION['expertise_subject'] ?>">
                  </div>

               </div>
            </div>
            <div class="application-form__card card-form" data-type="applicant">
               <div class="card-form__header">
                     <span class="card-form__title">
                        СВЕДЕНИЯ О ЗАЯВИТЕЛЕ
                     </span>
                  <i class="fas fa-chevron-down card-form__icon arrow-down"></i>
               </div>

               <div class="card-form__body body-card">

                  <div class="body-card__row" data-row_name="type_of_work" data-required="true">
                     <span class="body-card__title required">Вид работ</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-select">
                           <span class="modal-select__value">Выберите значение</span>
                           <i class="modal-select__icon fas fa-bars"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>

                     <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items"></div>
                     </div>
                     <input class="body-card__result" type="hidden" name="type_of_work">
                  </div>

                  <div class="body-card__row" data-row_name="functional_purpose" data-required="true">
                     <span class="body-card__title required">Функциональное назначение</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-select">
                           <span class="modal-select__value">Выберите значение</span>
                           <i class="modal-select__icon fas fa-bars"></i>
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
                     <input class="body-card__result" type="hidden" name="functional_purpose">
                  </div>

                  <div class="body-card__row" data-row_name="type_of_object" data-required="true">
                     <span class="body-card__title required">Вид объекта</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-select">
                           <span class="modal-select__value">Выберите значение</span>
                           <i class="modal-select__icon fas fa-bars"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>

                     <div class="modal">
                        <i class="modal__close fas fa-times"></i>
                        <div class="modal__items">
                           <?php foreach($typeOfWorksIH as $pageNumber => $page): ?>
                              <div class="modal__page" data-page="<?= $pageNumber ?>">
                                 <?php foreach($page as $item): ?>
                                    <div class="modal__item" data-id="<?= $item['id'] ?>"><?= $item['name'] ?></div>
                                 <?php endforeach; ?>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                     <input class="body-card__result" type="hidden" name="type_of_object">
                  </div>

                  <div class="body-card__row inactive" data-row_name="estimate_cost" data-pattern="cost">
                     <span class="body-card__title required">Сведения о сметной или предполагаемой (предельной) стоимости объекта капитального строительства, содержащиеся в решении по объекту или письме. тыс. руб.</span>
                     <div class="body-card__field">
                        <input class="body-card__input body-card__result application-input" type="text" name="estimate_cost" placeholder="Введите значение">
                        <span class="body-card__input-error">Поле обязательно для заполнения</span>
                     </div>
                  </div>


                  <div class="body-card__row" data-row_name="kpp" data-pattern="kpp">
                     <span class="body-card__title required">КПП</span>
                     <div class="body-card__field">
                        <input class="body-card__input body-card__result application-input" type="text" name="kpp" placeholder="Введите значение" maxlength="9">
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>
                  </div>

                  <div class="body-card__row" data-row_name="email" data-pattern="email">
                     <span class="body-card__title required">Email</span>
                     <div class="body-card__field">
                        <input class="body-card__input body-card__result application-input" type="text" name="email" placeholder="Введите значение">
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>
                  </div>

                  <div class="body-card__row" data-row_name="calendar-test" data-required="true">
                     <span class="body-card__title required">Дата</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-calendar">
                           <span class="modal-calendar__value">Выберите дату</span>
                           <i class="modal-calendar__icon fas fa-calendar-alt"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>

                     <input class="body-card__result" type="hidden" name="calendar-test">
                  </div>

                  <div class="body-card__row" data-row_name="calendar-test1" data-required="true">
                     <span class="body-card__title required">Дата</span>

                     <div class="body-card__field">
                        <div class="body-card__select modal-calendar">
                           <span class="modal-calendar__value">Выберите дату</span>
                           <i class="modal-calendar__icon fas fa-calendar-alt"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>

                     <input class="body-card__result" type="hidden" name="calendar-test1">
                  </div>

                  <div class="body-card__row" data-row_name="grbs" data-required="true" data-multiple="true" >
                     <span class="body-card__title required">Файл ГРБС</span>
                     <div class="body-card__field">
                        <div class="body-card__select" data-modal-type="file">
                           <span class="modal-select__title">Загрузите файл</span>
                           <i class="modal-select__icon fas fa-bars"></i>
                        </div>
                        <span class="body-card__error">Поле обязательно для заполнения</span>
                     </div>



                     <input class="body-card__result" type="hidden" name="grbs">
                  </div>

               </div>
            </div>
         </form>
      </>
   </main>
   <footer>
      <div class="footer__info footer-info">
         <div class="footer-info__item">
            <i class="fas fa-phone-alt"></i>
            <span class="footer-info__text">+7 (351) 219-33-00</span>
         </div>
         <div class="footer-info__item">
            <i class="fas fa-envelope"></i>
            <span class="footer-info__text">info@ge74.ru</span>
         </div>
         <div class="footer-info__item">
            <i class="fas fa-map-marker-alt"></i>
            <span class="footer-info__text">Цвиллинга 46, Приемная: оф.600</span>
         </div>
      </div>
   </footer>

</div>

<div class="modal-overlay"></div>
<div class="calendar-overlay"></div>

<div class="modal alert-modal">
   <span class="alert-modal__message"></span>
   <i class="modal__close fas fa-times"></i>
</div>

<div class="calendar">
   <div class="calendar__nav">
      <i class="calendar__arrow left fas fa-chevron-left"></i>
      <span class="calendar__selected_label"></span>
      <i class="calendar__arrow right fas fa-chevron-right"></i>
   </div>
   <div class="calendar__title">
      <div class="calendar__week-day">Пн</div>
      <div class="calendar__week-day">Вт</div>
      <div class="calendar__week-day">Ср</div>
      <div class="calendar__week-day">Чт</div>
      <div class="calendar__week-day">Пн</div>
      <div class="calendar__week-day">Сб</div>
      <div class="calendar__week-day">Вс</div>
   </div>
   <div class="calendar__body">

   </div>
</div>

<div class="modal" data-type="file">
   <form id="file_uploader" action="/" enctype="multipart/form-data">

   </form>
</div>

</body>

</html>