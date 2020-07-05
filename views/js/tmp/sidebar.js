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
