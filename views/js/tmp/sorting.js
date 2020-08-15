document.addEventListener('DOMContentLoaded', () => {
   let request_urn = '/home/API_navigation_cookie';
<<<<<<< HEAD
   let size_header = document.querySelector('.navigation-table__size-header');
   let size_buttons = size_header.querySelectorAll('.navigation-table__amount');

   size_buttons.forEach(button => {
      button.addEventListener('click', () => {
         if (button.dataset.is_selected !== '1') {

            let form_data = getNavigationTableSizeFormData(button);

            XHR('post', request_urn, form_data, null, 'json', null, null)
               .then(response => {

                  console.log(response);

                  switch (response.result) {
                     case 5:
                        changeSelectedSize(size_header, button);
                        window.location.reload();
                        break;
                     default:
                        break;
                  }

               })
               .catch(error => {
                  // p.s. все сообщения об ошибках везде делаем однотипными
                  console.error('XHR error: ', error);
                  // Ошибка XHR запроса. Обратитесь к администратору
               });
         }

      });
   });

   let sort_header = document.querySelector('.navigation-table__sort-header');
   let sort_buttons = sort_header.querySelectorAll('.navigation-table__category');
   sort_buttons.forEach(button => {
      button.addEventListener('click', () => {
         /*let sort_icon = document.createElement('I');
         if (button.dataset.sort_type === 'ASC') {
            button.dataset.sort_type = 'DESC';
            sort_icon.classList.add('navigation-table__sort-icon', 'fas', 'fa-caret-up');
         } else {

         }*/

         button.dataset.sort_type = button.dataset.sort_type === 'ASC' ? 'DESC' : 'ASC';



         let form_data = getNavigationTableSortFormData(button);

         XHR('post', request_urn, form_data, null, 'json', null, null)
            .then(response => {

               console.log(response);

               switch (response.result) {
                  case 5:

                     window.location.reload();
                     break;
                  default:
                     break;
               }

            })
            .catch(error => {
               // p.s. все сообщения об ошибках везде делаем однотипными
               console.error('XHR error: ', error);
               // Ошибка XHR запроса. Обратитесь к администратору
            });

      });
   });

});

function getNavigationTableSizeFormData(related_button) {
   let form_data = new FormData();
   let view_name = document.querySelector('[name="navigation__view-name"]').value;
   form_data.append('view_name', view_name);
   form_data.append('data_per_page', parseInt(related_button.dataset.per_page));
   return form_data;
}

function changeSelectedSize(size_header, new_size) {
   let selected_size = size_header.querySelector('[data-is_selected="1"]');
   selected_size.dataset.is_selected = '0';
   new_size.dataset.is_selected = '1';
}

=======

   handleChangeTableSizeButtons();
   handleChangeSortTypeButtons();

   // Предназначен для обработок кнопок изменения количества строк на странице в таблице навигации
   function handleChangeTableSizeButtons() {
      let size_header = document.querySelector('.navigation-table__size-header');

      let size_buttons = size_header.querySelectorAll('.navigation-table__amount');
      size_buttons.forEach(button => {
         button.addEventListener('click', () => {
            if (button.dataset.is_selected !== '1') {
               changeTableSize(button, size_header);
            }
         });
      });
   }

   // Предназначен для изменения количества строк на странице в таблице навигации
   // Принимает параметры-------------------------------
   // button         Element : кнопка с выбранным количеством строк
   // size_header    Element : родительский блок с кнопками
   function changeTableSize(button, size_header) {
      let form_data = getNavigationTableSizeFormData(button);

      XHR('post', request_urn, form_data, null, 'json', null, null)
         .then(response => {
            switch (response.result) {
               case 5:
                  changeSelectedSize(size_header, button);
                  window.location.reload();
                  break;
               default:
                  break;
            }

         })
         .catch(error => {
            // p.s. все сообщения об ошибках везде делаем однотипными
            console.error('XHR error: ', error);
            // Ошибка XHR запроса. Обратитесь к администратору
         });
   }

   // Предназначен для создания объекта FormData, содержащий текущее вью и новый размер таблицы
   // Принимает параметры-------------------------------
   // related_button      Element : кнопка с выбранным количеством строк
   // Возвращает параметры------------------------------
   // form_data          FormData : объект для изменения размера таблицы
   function getNavigationTableSizeFormData(related_button) {
      let form_data = new FormData();
      let view_name = document.querySelector('[name="navigation__view-name"]').value;
      form_data.append('view_name', view_name);
      form_data.append('data_per_page', related_button.dataset.per_page);
      return form_data;
   }

   // Предназначен для изменения выбранного размера таблицы навигации
   // Принимает параметры-------------------------------
   // size_header       Element : родительский блок с кнопками
   // new_size_button   Element : новый выбранный размер
   function changeSelectedSize(size_header, new_size_button) {
      let selected_size = size_header.querySelector('[data-is_selected="1"]');
      selected_size.dataset.is_selected = '0';
      new_size_button.dataset.is_selected = '1';
   }

   // Предназначен для обработки кнопок выбора сортировки таблицы навигации
   function handleChangeSortTypeButtons() {
      let sort_header = document.querySelector('.navigation-table__sort-header');

      let sort_buttons = sort_header.querySelectorAll('.navigation-table__category');
      sort_buttons.forEach(button => {
         button.addEventListener('click', () => {
            changeSortType(button);
         });
      });
   }

   // Предназначен для изменения сортировки таблицы навигации
   // Принимает параметры-------------------------------
   // button       Element : кнопка с выбранным типом сортировки
   function changeSortType(button) {
      button.dataset.sort_type = button.dataset.sort_type === 'ASC' ? 'DESC' : 'ASC';

      let form_data = getNavigationTableSortFormData(button);

      XHR('post', request_urn, form_data, null, 'json', null, null)
         .then(response => {

            switch (response.result) {
               case 5:
                  window.location.reload();
                  break;
               default:
                  break;
            }

         })
         .catch(error => {
            // p.s. все сообщения об ошибках везде делаем однотипными
            console.error('XHR error: ', error);
            // Ошибка XHR запроса. Обратитесь к администратору
         });
   }
});

// Предназначен для создания объекта FormData, содержащий текущее вью и выбранную сортировку
// Принимает параметры-------------------------------
// related_button      Element : кнопка с выбранным количеством строк
// Возвращает параметры------------------------------
// form_data          FormData : объект для изменения сортировки таблицы
>>>>>>> 5b015d9495abdca6a19a460370085b00167fbfbb
function getNavigationTableSortFormData(related_button) {
   let form_data = new FormData();
   let view_name = document.querySelector('[name="navigation__view-name"]').value;
   form_data.append('view_name', view_name);
   form_data.append('sort_name', related_button.dataset.sort_name);
   form_data.append('sort_type', related_button.dataset.sort_type);
   return form_data;
}












