document.addEventListener('DOMContentLoaded', () => {
   SignHandler.init();
});

class SignHandler {
   static modal;
   static overlay;
   static is_plugin_initialized = false;
   static sign_btn;
   static id_file;
   static fs_name_data;
   static fs_name_sign;
   static file_name;
   static mapping_level_1;
   static mapping_level_2;

   static init() {
      SignHandler.modal = document.querySelector('.sign-modal');

      SignHandler.handleOverlay();
      SignHandler.handleSignButton();
   }

   static handleOverlay() {
      SignHandler.overlay = document.querySelector('.sign-overlay');
      SignHandler.overlay.addEventListener('click', () => SignHandler.closeModal());
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
      let form_data = SignHandler.getFileCheckFormData(SignHandler.id_file);
      let selected_algorithm;

      XHR('post', '/home/API_file_checker', form_data, null, 'json')
         // Проверяем, что файл может быть скачан
         .then(response => {

            console.log(response);
            return response;

         })
         // Получаем алгоритм
         .then(check_response => {

            if (check_response.result === 9) {
               SignHandler.fs_name_data = check_response.fs_name;
               SignHandler.file_name = check_response.file_name;
               return GeCades.getSelectedCertificateAlgorithm();
            } else {
               console.log(check_response);
            }

         })
         // Вычисляем хэш файла
         .then(algorithm => {

            console.log(algorithm);
            selected_algorithm = algorithm;
            form_data = SignHandler.getFileHashFormData(algorithm);
            return XHR('post', '/home/API_get_file_hash', form_data, null, 'json');

         })
         // Получаем хэш подписи
         .then(file_hash => {

            return GeCades.SignHash_Async(selected_algorithm, file_hash.hash);

         })
         // Создаем файл подписи и загружаем его
         .then(sign_hash => {

            form_data = SignHandler.getFileUploadFormData(sign_hash);
            return XHR('post', '/home/API_file_uploader', form_data, null, 'json', null, null);

         })
         // Проверяем, что подпись может быть скачана
         .then(upload_response => {

            console.log(upload_response);
            if (upload_response.result === 16) {
               let id_sign = upload_response.uploaded_files[0].id;
               form_data = SignHandler.getFileCheckFormData(id_sign);
               return XHR('post', '/home/API_file_checker', form_data, null, 'json');
            } else {
               console.log('upload sign exception');
            }

         })
         // Проверяем подпись
         .then(check_sign_response => {

            if (check_sign_response.result === 9) {

               SignHandler.fs_name_sign = check_sign_response.fs_name;
               form_data = SignHandler.getSignVerifyFormData();
               return XHR('post', '/home/API_external_signature_verifier', form_data, null, 'json', null, null)

            } else {
               console.log(check_sign_response);
            }

         })
         .then(verify_response => {

            console.log(verify_response);

         })
         .catch(exception => {
            console.log(exception);
         });
   }

   static getFileCheckFormData(id_file) {
      let form_data = new FormData();
      form_data.append('id_application', getIdApplication());
      form_data.append('id_file', id_file);
      form_data.append('mapping_level_1', SignHandler.mapping_level_1);
      form_data.append('mapping_level_2', SignHandler.mapping_level_2);
      return form_data;
   }

   static getFileHashFormData(algorithm) {
      let form_data = new FormData();
      form_data.append('sign_algorithm', algorithm);
      form_data.append('fs_name', SignHandler.fs_name_data);
      return form_data;
   }

   static getFileUploadFormData(sign_hash) {
      let form_data = new FormData();
      form_data.append('id_application', getIdApplication());
      form_data.append('mapping_level_1', SignHandler.mapping_level_1);
      form_data.append('mapping_level_2', SignHandler.mapping_level_2);
      let sign_blob = new Blob([sign_hash], {type: 'text/plain'});
      form_data.append('download_files[]', sign_blob, SignHandler.file_name + '.sig');
      return form_data;
   }

   static getSignVerifyFormData() {
      let form_data = new FormData();
      form_data.append('fs_name_data', SignHandler.fs_name_data);
      form_data.append('fs_name_sign', SignHandler.fs_name_sign);
      form_data.append('mapping_level_1', SignHandler.mapping_level_1);
      form_data.append('mapping_level_2', SignHandler.mapping_level_2);
      return form_data;
   }

   static openModal(file) {
      //TODO открывать послен инициализации
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



}