document.addEventListener('DOMContentLoaded', () => {
   let save_button = document.getElementById('application_save');

   save_button.addEventListener('click', () => {
      saveApplication();
   });



});

function saveApplication () {
   FileNeeds.putFilesToFileNeeds();

   let form_data = getSaveApplicationFormData();
   let multiple_blocks = MultipleBlock.appendMultipleBlocks(form_data);

   console.log(new Map(form_data));

   XHR(
      'post',
      '/home/application/API_save_form',
      form_data,
      null,
      'json',
      null,
      null
   )
      .then(response => {

         if (response.result !== undefined) {

            switch (response.result) {
               case 8:
                  MultipleBlock.saveMultipleBlocks(multiple_blocks);
                  if (FileNeeds.hasFiles()) {
                     API.updateFileNeeds();
                  }
                  showAlertModal();
                  break;
               case 1:
                  ErrorModal.open('Ошибка при сохранении заявления', 'Нет обязательных параметров POST запроса');
                  break;

               default:
                  ErrorModal.open('Ошибка при сохранении заявления', response.error_message || response.message);
            }

         } else {
            ErrorModal.open('Ошибка при сохранении заявления', 'Не получен результат сохранения заявления');
         }



      })
      .catch(error => {
         ErrorModal.open('Ошибка при сохранении заявления', error.message);
      });
}

function getSaveApplicationFormData() {
   let form_data = new FormData();

   // MultipleBlock.appendMultipleBlocks(form_data);

   let id_application = document.querySelector('[name="id_application"]').value;
   form_data.append('id_application', id_application);

   let fields = document.querySelectorAll('[data-field_result]:not([data-multiple_block_field])');
   fields.forEach(field => {

      if (!field.closest('[data-block][data-active="false"]')) {
         form_data.append(field.name, field.value);
      } else {
         form_data.append(field.name, '');
      }

   });

   return form_data;
}




