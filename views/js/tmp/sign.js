document.addEventListener('DOMContentLoaded', () => {
   let sign_modal = document.querySelector('.sign-modal');

   let signature_button = document.getElementById('signature_button');
   signature_button.addEventListener('click', () => {

      if (GeCades.getGlobalCertificatesMap) {
         console.log('asd');
         signFile(sign_modal);
      } else {
         console.log('zxc');
      }


   });



   let sign_overlay = document.querySelector('.sign-overlay');
   sign_overlay.addEventListener('click', () => {
      closeSignModal();
   });
});

function signFile(sign_modal) {
   GeCades.getSelectedCertificateAlgorithm()
      .then(algorithm => {

         checkFile(sign_modal, algorithm);


      })
      .catch(error => {
         console.error('XHR error: ', error);
      });
}

function showSignModal(file) {
   let sign_modal = document.querySelector('.sign-modal');
   let sign_overlay = document.querySelector('.sign-overlay');
   sign_modal.classList.add('active');
   sign_overlay.classList.add('active');

   if (sign_modal.dataset.plugin_loaded === 'false') {
      sign_modal.dataset.plugin_loaded = 'true';
      BrowserHelper.initializePlugin(sign_modal);
   }

   putDataAboutFile(file, sign_modal);
   addFileElement(file, sign_modal);
}

function cancelPluginInitialization() {
   let sign_modal = document.querySelector('.sign-modal');
   sign_modal.dataset.plugin_loaded = 'false';
}

function putDataAboutFile(file, sign_modal) {
   let parent_field = file.closest('[data-mapping_level_1]');
   sign_modal.dataset.id_file = file.dataset.id;
   sign_modal.dataset.mapping_level_1 = parent_field.dataset.mapping_level_1;
   sign_modal.dataset.mapping_level_2 = parent_field.dataset.mapping_level_2;
}

function addFileElement(file, sign_modal) {
   let file_info = file.querySelector('.files__info');
   let sign_file = sign_modal.querySelector('.sign-modal__file');
   sign_file.innerHTML = file_info.innerHTML;
}

function checkFile(sign_modal, algorithm) {
   let request_urn = '/home/API_file_checker';
   let form_data = getFileCheckFormData(sign_modal);

   //Проверяем, что файл может быть скачан
   XHR('post', request_urn, form_data, null, 'json')
      .then(response => {
         console.log(response);

         if (response.result === 9) {
            calcFileHash(sign_modal, algorithm);
         }

      })
      .catch(error => {
         console.error('XHR error: ', error);
         return null;
      });

}

function getFileCheckFormData(sign_modal) {
   let form_data = new FormData();
   form_data.append('id_application', getIdApplication());
   form_data.append('id_file', sign_modal.dataset.id_file);
   form_data.append('mapping_level_1', sign_modal.dataset.mapping_level_1);
   form_data.append('mapping_level_2', sign_modal.dataset.mapping_level_2);
   return form_data;
}

function calcFileHash(sign_modal, algorithm) {
   let request_urn = '/home/API_get_file_hash';
   let form_data = getFileHashFormData(sign_modal, algorithm);

   XHR('post', request_urn, form_data, null, 'json')
   .then(response => {

      console.log(response);

      let hash = response.hash;

      GeCades.SignHash_Async(algorithm, hash)
         .then(signature => {
            console.log(signature);
         })
         .catch(exception => {
            console.log(exception);
         });

   })
   .catch(error => {
      console.error('XHR error: ', error);
   });
}

function getFileHashFormData(sign_modal, algorithm) {
   let form_data = new FormData();
   form_data.append('sign_algorithm', algorithm.toString());
   form_data.append('id_file', sign_modal.dataset.id_file);
   form_data.append('mapping_level_1', sign_modal.dataset.mapping_level_1);
   form_data.append('mapping_level_2', sign_modal.dataset.mapping_level_2);
   return form_data;
}

function closeSignModal() {
   let sign_modal = document.querySelector('.sign-modal');
   let sign_overlay = document.querySelector('.sign-overlay');
   sign_modal.classList.remove('active');
   sign_overlay.classList.remove('active');
}