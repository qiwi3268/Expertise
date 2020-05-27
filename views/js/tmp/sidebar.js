document.addEventListener('DOMContentLoaded', () => {

   let sidebar_rows = document.querySelectorAll('.sidebar-form__row');
   let selected_row;

   sidebar_rows.forEach(row => row.addEventListener('click', () => {
      // Если есть выбранный элемент снимаем выделение
      selected_row = document.querySelector('.sidebar-form__row.selected');
      if (selected_row) {
         selected_row.classList.remove('selected');
      }

      let related_card = document.querySelector(`.card-form[data-type='${row.dataset.card}']`);
      //expandCard(related_card);

      // Выделяем элемент и добавляем линию слева
      row.classList.add('selected');
   }));



});

function expandCard(card) {
   let card_arrow;
   let card_body;
   if (card) {
      card_arrow = card.querySelector('.card-form__icon');
      card_body = card.querySelector('.card-form__body');

      //переворачиваем стрелку
      card_arrow.classList.toggle('arrow-down');
      card_arrow.classList.toggle('arrow-up');

      // let card_body = card.querySelector('.body-card');
      // Раскрываем блок
      // todo исправить
      if (!card_body.style.minHeight) {


         card_body.style.minHeight = card_body.scrollHeight + "px";
         card_body.style.maxHeight = '100%';

      } else {
         card_body.style.minHeight = null;
         card_body.style.maxHeight = null;
      }

      /*// Раскрываем блок
      if (!card_body.style.maxHeight) {
         card_body.style.maxHeight = card_body.scrollHeight + "px";
      }*/
   }
}

function setSidebarItemState(sidebar_item, is_valid) {
   let sidebar_icon = sidebar_item.querySelector('.sidebar-form__icon');

   if (is_valid) {
      sidebar_item.classList.add('valid');
      sidebar_item.classList.remove('warning');
      sidebar_icon.classList.add('fa-check', 'valid');
      sidebar_icon.classList.remove('fa-exclamation', 'warning');
   } else {
      sidebar_item.classList.add('warning');
      sidebar_item.classList.remove('valid');
      sidebar_icon.classList.add('fa-exclamation', 'warning');
      sidebar_icon.classList.remove('fa-check', 'valid');
   }
}
