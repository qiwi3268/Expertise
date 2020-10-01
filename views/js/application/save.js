document.addEventListener('DOMContentLoaded', () => {
   let save_button = document.getElementById('application_save');

   save_button.addEventListener('click', () => {
      saveApplication();
   });

   let save_overlay = document.querySelector('.save-overlay');
   save_overlay.addEventListener('click', () => {
      closeSaveModal(save_overlay);
   });

   let save_close_button = document.querySelector('.save-modal__close');
   save_close_button.addEventListener('click', () => {
      closeSaveModal(save_overlay);
   });
});

function closeSaveModal (save_overlay) {
   let save_modal = document.querySelector('.save-modal');
   save_modal.classList.remove('active');
   save_overlay.classList.remove('active');
}

function saveApplication () {
   FileNeeds.putFilesToFileNeeds();

   let form_data = getSaveApplicationFormData();

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

         switch (response.result) {
            case 8:
               if (FileNeeds.hasFiles()) {
                  API.updateFileNeeds();
               }
               showSaveModal();
               break;
            case 1:
               ErrorModal.open('Ошибка при сохранении заявления', 'Нет обязательных параметров POST запроса');
               break;

            default:
               ErrorModal.open('Ошибка при сохранении заявления', response.error_message || response.message);

               console.log(response);
         }

      })
      .catch(error => {
         ErrorModal.open('Ошибка при сохранении заявления', error.message);
      });
}

function saveMultipleBlocks (form_data) {

   let multiple_blocks = document.querySelectorAll('[data-block][data-type="multiple"]');
   multiple_blocks.forEach(block => {

      let multiple_block = MultipleBlock.getBlockByName(block.dataset.name);
      if (multiple_block.is_changed) {
         multiple_block.is_changed = false;
         form_data.append('finance_sources_exist_flag', '1');
      } else {
         form_data.append('finance_sources_exist_flag', '0');
      }

      form_data.append(block.dataset.name, multiple_block.getPartsDataJSON());

   });
}

function getSaveApplicationFormData() {
   let form_data = new FormData();

   saveMultipleBlocks(form_data);

   let id_application = document.querySelector('[name="id_application"]').value;
   form_data.append('id_application', id_application);

   let fields = document.querySelectorAll('.field-result:not([data-multiple_block_field])');
   fields.forEach(field => {

      if (!field.closest('[data-block][data-active="false"]')) {
         form_data.append(field.name, field.value);
      } else {
         form_data.append(field.name, '');
      }

   });

   return form_data;
}

function showSaveModal () {
   let save_modal = document.querySelector('.save-modal');
   let save_overlay = document.querySelector('.save-overlay');
   save_modal.classList.add('active');
   save_overlay.classList.add('active');
}
