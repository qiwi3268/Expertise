document.addEventListener('DOMContentLoaded', () => {
   SignHandler.init();
});

class SignHandler {
   static modal;
   static overlay;
   static is_plugin_initialized = false;
   static sign_btn;
   static id_application;
   static id_file;
   static file_name;
   static mapping_level_1;
   static mapping_level_2;


   static signature;

   static init() {
      SignHandler.modal = document.querySelector('.sign-modal');

      SignHandler.handleOverlay();
      SignHandler.handleSignButton();
   }

   static handleSignButton() {
      SignHandler.sign_btn = document.getElementById('signature_button');
      SignHandler.sign_btn.addEventListener('click', () => {
         if (GeCades.getGlobalCertificatesMap) {
            SignHandler.signFile();
         }
      });
   }


   static signFile() {
      let form_data = SignHandler.getFileCheckFormData();
      let selected_algorithm;
      let fs_name;

      XHR('post', '/home/API_file_checker', form_data, null, 'json')
         .then(response => {
            console.log(response);
            return response;
         })
         //Проверяем, что файл может быть скачан
         .then(check_response => {
            if (check_response.result === 9) {
               fs_name = check_response.fs_name;
               SignHandler.file_name = check_response.file_name;
               return GeCades.getSelectedCertificateAlgorithm();
            } else {
               console.log(check_response);
            }
         })
         //Получаем алгоритм
         .then(algorithm => {
            console.log(algorithm);
            selected_algorithm = algorithm;
            form_data = SignHandler.getFileHashFormData(algorithm, fs_name);
            return XHR('post', '/home/API_get_file_hash', form_data, null, 'json');
         })
         //Вычисляем хэш файла
         .then(file_hash => {
            return GeCades.SignHash_Async(selected_algorithm, file_hash.hash)
         })
         //Получаем подпись
         .then(sign_hash => {
            form_data = SignHandler.getFileUploadFormData(sign_hash);
            console.log(new Map(form_data));

            return XHR('post', '/home/API_file_uploader', form_data, null, 'json', null, null)
         })
         .then(upload_response => {
            console.log(upload_response);
         })
         .catch(exception => {
            console.log(exception);
         });
   }

   static getFileUploadFormData(sign_hash) {
      let form_data = new FormData();
      let sign = getFileFromData(sign_hash, SignHandler.file_name);

      let input = document.getElementById('external_sign');
      console.log(input);
      input.files = getFileListFromFile(sign);


      // let files = getFileListFromFile(getFileFromData(sign_hash, SignHandler.file_name));
      form_data.append('id_application', getIdApplication());
      form_data.append('mapping_level_1', SignHandler.mapping_level_1);
      form_data.append('mapping_level_2', SignHandler.mapping_level_2);
      form_data.append('download_files[]', input.files);
      return form_data;
   }

   static getFileCheckFormData() {
      let form_data = new FormData();
      form_data.append('id_application', getIdApplication());
      form_data.append('id_file', SignHandler.id_file);
      form_data.append('mapping_level_1', SignHandler.mapping_level_1);
      form_data.append('mapping_level_2', SignHandler.mapping_level_2);
      return form_data;
   }

   static getFileHashFormData(algorithm, fs_name) {
      let form_data = new FormData();
      form_data.append('sign_algorithm', algorithm);
      form_data.append('fs_name', fs_name);
      return form_data;
   }

   static openModal(file) {
      SignHandler.modal.classList.add('active');
      SignHandler.overlay.classList.add('active');

      if (!SignHandler.is_plugin_initialized) {
         BrowserHelper.initializePlugin();
         SignHandler.is_plugin_initialized = true;
      }

      SignHandler.putFileData(file);
      SignHandler.addFileElement(file);
   }

   static putFileData(file) {
      let parent_field = file.closest('[data-mapping_level_1]');
      SignHandler.id_file = file.dataset.id;
      SignHandler.mapping_level_1 = parent_field.dataset.mapping_level_1;
      SignHandler.mapping_level_2 = parent_field.dataset.mapping_level_2;
   }

   static addFileElement(file) {
      let file_info = file.querySelector('.files__info');
      let sign_file = SignHandler.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }

   static cancelPluginInitialization() {
      SignHandler.is_plugin_initialized = false;
      SignHandler.closeModal();
   }

   static closeModal() {
      SignHandler.modal.classList.remove('active');
      SignHandler.overlay.classList.remove('active');
   }

   static handleOverlay() {
      SignHandler.overlay = document.querySelector('.sign-overlay');
      SignHandler.overlay.addEventListener('click', () => SignHandler.closeModal());
   }

}