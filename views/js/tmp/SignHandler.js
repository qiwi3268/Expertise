document.addEventListener('DOMContentLoaded', () => {
   //TODO сделать синглтоном
   SignHandler.init();
});

class SignHandler {
   static modal;
   static overlay;
   static is_plugin_initialized = false;



   static actions;
   static sign_create_actions;

   static cert_select;

   static sign_btn;
   static upload_sign_btn;
   static sign_delete_btn;

   static validate_info;
   static cert_state;
   static sign_state;


   static id_file;
   static id_sign;

   static sign_input;

   static fs_name_data;
   static fs_name_sign;
   static file_name;
   static mapping_level_1;
   static mapping_level_2;

   static init() {
      SignHandler.modal = document.querySelector('.sign-modal');

      SignHandler.actions = SignHandler.modal.querySelector('.sign-modal__actions');
      SignHandler.sign_create_actions = SignHandler.modal.querySelector('.sign-modal__sign-actions');

      SignHandler.cert_select = SignHandler.modal.querySelector('.sign-modal__select');

      SignHandler.validate_info = SignHandler.modal.querySelector('.sign-modal__validate');



      SignHandler.handleOverlay();
      SignHandler.handleSignButton();

      SignHandler.handleUploadSignButton();

      SignHandler.handleDeleteButton();


      // SignHandler.handle();

      let sign_btn = document.getElementById('signature_button');
      sign_btn.addEventListener('click', () => {



         if (GeCades.getGlobalCertificatesMap) {


            SignHandler.signFile();
         }

      });

      let cancel_btn = document.getElementById('sign_cancel');
      cancel_btn.addEventListener('click', () => {

         SignHandler.upload_sign_btn.dataset.inactive = 'false';
         SignHandler.sign_btn.dataset.inactive = 'false';

         SignHandler.actions.dataset.inactive = 'false';
         SignHandler.sign_create_actions.dataset.inactive = 'true';
         SignHandler.cert_select.dataset.inactive = 'true';

      });

   }

   static handleOverlay() {
      SignHandler.overlay = document.querySelector('.sign-overlay');
      SignHandler.overlay.addEventListener('click', () => SignHandler.closeModal());
   }

   static handleSignButton() {
      SignHandler.sign_btn = document.getElementById('sign_create');
      SignHandler.sign_btn.addEventListener('click', () => {



         // if (!SignHandler.is_plugin_initialized) {

            BrowserHelper.initializePlugin();

         // }



      });

   }

   static handleUploadSignButton() {
      SignHandler.sign_input = document.getElementById('external_sign');
      SignHandler.sign_input.addEventListener('change', () => {

         let form_data = new FormData();
         form_data.append('id_application', getIdApplication());
         form_data.append('mapping_level_1', SignHandler.mapping_level_1);
         form_data.append('mapping_level_2', SignHandler.mapping_level_2);
         form_data.append('download_files[]', SignHandler.sign_input.value);

         let files = Array.from(SignHandler.sign_input.files);
         files.forEach(file => {

            form_data.append('download_files[]', file);

         });


         let upload_response;
         let fs_name_data;
         let fs_name_sign;


         XHR('post', '/home/API_file_uploader', form_data, null, 'json', null, null)
            .then(response => {
               let form_data = SignHandler.getFileCheckFormData(SignHandler.id_file);

               if (response.result === 16) {
                  upload_response = response;
                  return XHR('post', '/home/API_file_checker', form_data, null, 'json');
               } else {
                  console.log('upload sign exception');
               }

            })
            // Проверяем, что файл может быть скачан
            .then(file_check_response => {
               SignHandler.fs_name_data = file_check_response.fs_name;

               SignHandler.id_sign = upload_response.uploaded_files[0].id;
               form_data = SignHandler.getFileCheckFormData(SignHandler.id_sign);
               return XHR('post', '/home/API_file_checker', form_data, null, 'json');

            })
            .then(sign_check_response => {
               SignHandler.fs_name_sign = sign_check_response.fs_name;

               form_data = SignHandler.getSignVerifyFormData();
               return XHR('post', '/home/API_external_signature_verifier', form_data, null, 'json', null, null)

            })
            .then(validate_response => {

               console.log(validate_response);

               let file = document.querySelector(`.files__item[data-id='${SignHandler.id_file}']`);
               file.dataset.id_sign = SignHandler.id_sign;
               file.dataset.validate_results = JSON.stringify(validate_response.validate_results);

               let results = JSON.parse(file.dataset.validate_results);
               results.forEach(result => {
                  SignHandler.handleValidateResults(result, file);
               });

               SignHandler.sign_create_actions.dataset.inactive = 'true';
               SignHandler.sign_delete_btn.dataset.inactive = 'false';



            })
            .catch(exc => {
               console.error(exc);
            });


      });


      SignHandler.upload_sign_btn = document.getElementById('sign_upload');
      SignHandler.upload_sign_btn.addEventListener('click', () => {

         SignHandler.sign_input.click();

      });

   }


   static showCertBlock() {
      SignHandler.is_plugin_initialized = true;

      SignHandler.cert_select.dataset.inactive = 'false';

      let plugin_info = SignHandler.modal.querySelector('.sign-modal__header');
      plugin_info.dataset.inactive = 'false';

      let sign_create_actions = SignHandler.modal.querySelector('.sign-modal__sign-actions');
      sign_create_actions.dataset.inactive = 'false';

      // SignHandler.actions.dataset.inactive = 'true';

      SignHandler.upload_sign_btn.dataset.inactive = 'true';
      SignHandler.sign_btn.dataset.inactive = 'true';

   }

   static handleDeleteButton() {
      SignHandler.sign_delete_btn = document.getElementById('signature_delete');
      SignHandler.sign_delete_btn.addEventListener('click', () => {

         //TODO файл подписи в file_needs


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
               SignHandler.id_sign = upload_response.uploaded_files[0].id;
               form_data = SignHandler.getFileCheckFormData(SignHandler.id_sign);
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
         .then(validate_response => {

            console.log(validate_response);

            let file = document.querySelector(`.files__item[data-id='${SignHandler.id_file}']`);
            file.dataset.id_sign = SignHandler.id_sign;
            file.dataset.validate_results = JSON.stringify(validate_response.validate_results);

            let results = JSON.parse(file.dataset.validate_results);
            results.forEach(result => {
               SignHandler.handleValidateResults(result, file);
            });

            SignHandler.sign_create_actions.dataset.inactive = 'true';
            SignHandler.sign_delete_btn.dataset.inactive = 'false';



         })
         .catch(exception => {
            console.log(exception);
         });
   }

   static handleValidateResults(validate_result, file) {

      //TODO сделать в цикле


      if (validate_result.signature_verify.result && validate_result.certificate_verify.result) {

         file.dataset.sign_state = 'valid';
      } else if (!validate_result.signature_verify.result) {
         file.dataset.sign_state = 'invalid';
      } else {
         file.dataset.sign_state = 'warning';
      }

      // if (file.dataset.is_internal_sign !== 'true') {

         SignHandler.validate_info.dataset.inactive = 'false';
         SignHandler.cert_state = document.getElementById('ValidateCertState');
         SignHandler.cert_state.innerHTML = validate_result.certificate_verify.user_message;
         SignHandler.cert_state.dataset.state = validate_result.certificate_verify.result;

         SignHandler.sign_state = document.getElementById('ValidateSignState');
         SignHandler.sign_state.innerHTML = validate_result.signature_verify.user_message;
         SignHandler.sign_state.dataset.state = validate_result.signature_verify.result;

      // }

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
      SignHandler.modal.classList.add('active');
      SignHandler.overlay.classList.add('active');

      if (!file.dataset.validate_results) {

         SignHandler.sign_btn.dataset.inactive = 'false';
         SignHandler.upload_sign_btn.dataset.inactive = 'false';

      } else if (file.dataset.is_internal_sign !== 'true') {

         SignHandler.sign_delete_btn.dataset.inactive = 'false';

         let results = JSON.parse(file.dataset.validate_results);
         results.forEach(result => {
            SignHandler.handleValidateResults(result, file);
         });

      } else {

         let results = JSON.parse(file.dataset.validate_results);
         results.forEach(result => {
            SignHandler.handleValidateResults(result, file);
         });

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


      SignHandler.sign_btn.dataset.inactive = 'true';
      SignHandler.upload_sign_btn.dataset.inactive = 'true';
      SignHandler.sign_delete_btn.dataset.inactive = 'true';



      SignHandler.cert_select.dataset.inactive = 'true';

      SignHandler.sign_create_actions.dataset.inactive = 'true';


      SignHandler.validate_info.dataset.inactive = 'true';
   }



}