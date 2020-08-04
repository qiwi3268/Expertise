document.addEventListener('DOMContentLoaded', () => {
   let request_urn = '/home/API_navigation_cookie';
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

function getNavigationTableSortFormData(related_button) {
   let form_data = new FormData();
   let view_name = document.querySelector('[name="navigation__view-name"]').value;
   form_data.append('view_name', view_name);
   form_data.append('sort_name', related_button.dataset.sort_name);
   form_data.append('sort_type', related_button.dataset.sort_type);
   return form_data;
}












