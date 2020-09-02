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
   let application_form = document.getElementById('application');
   let request_urn = '/home/application/API_save_form';

   FileNeeds.putFilesToFileNeeds();

   saveMultipleBlocks();


   XHR('post', request_urn, new FormData(application_form), null, 'json', null, null)
      .then(response => {

         switch (response.result) {
            case 8:
               if (FileNeeds.hasFiles()) {
                  updateFileNeeds();
               }
               showSaveModal();
               break;
            default:
               console.log(response);
         }

      })
      .catch(error => {
         alert(error.result);
         alert(error.message);
         console.error('XHR error: ', error);
      });
}

function saveMultipleBlocks () {

   let multiple_blocks = document.querySelectorAll('.block[data-type="multiple"]');
   multiple_blocks.forEach(block => {

      let multiple_block = MultipleBlock.getBlockByName(block.dataset.name);
      if (multiple_block.is_changed) {

         multiple_block.is_changed = false;

         let block_result = block.querySelector(`.field-result[name='${block.dataset.name}']`);

         block_result.value = multiple_block.getPartsDataJSON();
      }

   });
}
//
function getSaveApplicationFormData() {
   let form_data = new FormData();
   let fields = document.querySelectorAll('.field-result[data-form="application"]');
   fields.forEach(field => form_data.append(field.name, field.value));
   return form_data;
}

function showSaveModal () {
   let save_modal = document.querySelector('.save-modal');
   let save_overlay = document.querySelector('.save-overlay');
   save_modal.classList.add('active');
   save_overlay.classList.add('active');
}

//todo вынести
function updateFileNeeds () {
   let request_urn = '/home/API_file_needs_setter';
   let form_data = getFilesNeedsFormData();

   console.log(FileNeeds.getFileNeedsJSON());

   XHR('post', request_urn, form_data, null, 'json', null, null)
      .then(response => {

         switch (response.result) {
            case 9:
               console.log(response);
               FileNeeds.clear();
               break;
            default:
               console.log(response);
         }


      })
      .catch(error => {
         alert(error.result);
         alert(error.message);
         console.error('XHR error: ', error);
      });
}

function getFilesNeedsFormData () {
   let form_data = new FormData();
   form_data.append('id_application', getIdDocument());
   form_data.append('file_needs_json', FileNeeds.getFileNeedsJSON());
   return form_data;
}