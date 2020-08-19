document.addEventListener('DOMContentLoaded', () => {
   //TODO сделать синглтоном

   //SignHandler.init();
});

class SignHandler {
   static modal;
   static overlay;
   static is_plugin_initialized = false;



   static actions;

   static certs;
   static certs_select;

   static create_sign_btn;
   static upload_sign_btn;
   static delete_sign_btn;

   static plugin_info;

   static validate_info;
   static cert_state;
   static sign_state;

   static file_element;
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

      SignHandler.certs = SignHandler.modal.querySelector('.sign-modal__certs');
      SignHandler.plugin_info = SignHandler.modal.querySelector('.sign-modal__header');

      // SignHandler.actions = SignHandler.modal.querySelector('.sign-modal__buttons');
      SignHandler.actions = SignHandler.modal.querySelector('.sign-modal__actions');



      SignHandler.validate_info = SignHandler.modal.querySelector('.sign-modal__validate');



      SignHandler.handleOverlay();
      SignHandler.handleSignButton();
      SignHandler.handleUploadSignButton();
      SignHandler.handleDeleteButton();



      let sign_btn = document.getElementById('signature_button');
      sign_btn.addEventListener('click', () => {

         // if (GeCades.getGlobalCertificatesMap) {
            SignHandler.signFile();
         // }

      });

      let cancel_btn = document.getElementById('sign_cancel');
      cancel_btn.addEventListener('click', () => {

         SignHandler.upload_sign_btn.dataset.inactive = 'false';
         SignHandler.create_sign_btn.dataset.inactive = 'false';

         // SignHandler.actions.dataset.inactive = 'false';
         SignHandler.actions.dataset.inactive = 'true';
         SignHandler.certs.dataset.inactive = 'true';
         SignHandler.plugin_info.inactive = 'true';

      });

   }

   static handleOverlay() {
      SignHandler.overlay = document.querySelector('.sign-overlay');
      SignHandler.overlay.addEventListener('click', () => SignHandler.closeModal());
   }

   static handleSignButton() {
      SignHandler.create_sign_btn = document.getElementById('sign_create');
      SignHandler.create_sign_btn.addEventListener('click', () => {

         if (!SignHandler.is_plugin_initialized && BrowserHelper.checkBrowser()) {
            SignHandler.initializePlugin();
         } else {
            SignHandler.showCreateSignElements();
         }

      });
   }

   static initializePlugin() {

      GeCades.getCadesPlugin()
         .then(() => {

            return GeCades.getPluginData();

         })
         .then(plugin_data => {

            SignHandler.putPluginData(plugin_data);
            return GeCades.getCerts();

         })

         .then(certs => {

            SignHandler.fillCertsSelect(certs);
            SignHandler.showCreateSignElements();
            SignHandler.is_plugin_initialized = true;

         })
         .catch(exc => {
            console.log('Ошибка инициализации плагина и зполнения списка сертификатов:');
            console.log(exc);
            SignHandler.cancelPluginInitialization();
         });


   }

   static putPluginData(plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   static fillCertsSelect(certs) {
      SignHandler.certs_select = document.getElementById('cert_list_select');

      certs.forEach(cert => {
         let option = document.createElement('option');
         option.text = cert.text;
         option.value = cert.value;
         option.classList.add('sign-modal__cert');
         SignHandler.certs_select.options.add(option);
      });


      GeCades.setCertificatesList(SignHandler.certs_select);
      SignHandler.certs_select.addEventListener('change', () => {

         GeCades.getCertInfo()
            .then(cert_info => {
               SignHandler.fillCertInfo(cert_info);
            })
            .catch(exc => {
               console.log('Ошибка при получении информации о сертификате: ' + exc);
            });
      });


   }

   static fillCertInfo(cert_info) {
      // Внесение данных о сертификате
      document.getElementById('subject_name').innerHTML = cert_info.subject_name;
      document.getElementById('issuer_name').innerHTML = cert_info.issuer_name;
      document.getElementById('valid_from_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_from_date);
      document.getElementById('valid_to_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_to_date);
      document.getElementById('cert_message').innerHTML = cert_info.cert_message;
      document.getElementById('cert_message').style.color = cert_info.cert_status ? '#6cb37e' : '#db5151';

      SignHandler.plugin_info = SignHandler.modal.querySelector('.sign-modal__cert-info');
      SignHandler.plugin_info.dataset.inactive = 'false';
   }

   static showCreateSignElements() {
      SignHandler.certs.dataset.inactive = 'false';
      SignHandler.plugin_info.dataset.inactive = 'false';

      SignHandler.actions.dataset.inactive = 'false';

      SignHandler.upload_sign_btn.dataset.inactive = 'true';
      SignHandler.create_sign_btn.dataset.inactive = 'true';
   }

   //------
   static showCertBlock() {
      SignHandler.certs.dataset.inactive = 'false';

      SignHandler.plugin_info = SignHandler.modal.querySelector('.sign-modal__header');
      SignHandler.plugin_info.dataset.inactive = 'false';

      SignHandler.actions.dataset.inactive = 'false';

      SignHandler.upload_sign_btn.dataset.inactive = 'true';
      SignHandler.create_sign_btn.dataset.inactive = 'true';
   }

   static handleUploadSignButton() {
      SignHandler.sign_input = document.getElementById('external_sign');
      SignHandler.sign_input.addEventListener('change', () => {


         if (SignHandler.sign_input.files.length > 0) {


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

                  switch (validate_response.result) {

                     case 9:
                        let file = document.querySelector(`.files__item[data-id='${SignHandler.id_file}']`);
                        file.dataset.id_sign = SignHandler.id_sign;
                        file.dataset.validate_results = JSON.stringify(validate_response.validate_results);

                        let results = JSON.parse(file.dataset.validate_results);
                        results.forEach(result => {
                           SignHandler.handleValidateResults(result, file);
                        });

                        SignHandler.file_element.dataset.id_sign = SignHandler.id_sign;

                        SignHandler.create_sign_btn.dataset.inactive = 'true';
                        SignHandler.upload_sign_btn.dataset.inactive = 'true';
                        SignHandler.delete_sign_btn.dataset.inactive = 'false';

                        FileNeeds.putSignToSave(
                           SignHandler.id_sign,
                           SignHandler.mapping_level_1,
                           SignHandler.mapping_level_2
                        );

                        break;

                     case 6.1:
                        console.log(validate_response.error_message);

                        break;

                  }

               })
               .catch(exc => {
                  console.error(exc);
               });

         }

      });


      SignHandler.upload_sign_btn = document.getElementById('sign_upload');
      SignHandler.upload_sign_btn.addEventListener('click', () => {

         SignHandler.sign_input.click();

      });

   }



   static handleDeleteButton() {
      SignHandler.delete_sign_btn = document.getElementById('signature_delete');
      SignHandler.delete_sign_btn.addEventListener('click', () => {


         let id_sign = SignHandler.file_element.dataset.id_sign;

         FileNeeds.putSignToDelete(
            id_sign,
            SignHandler.mapping_level_1,
            SignHandler.mapping_level_2
         );


         SignHandler.file_element.removeAttribute('data-id_sign');
         SignHandler.file_element.removeAttribute('data-validate_results');
         SignHandler.sign_input.value = '';


         SignHandler.validate_info.dataset.inactive = 'true';
         SignHandler.certs.dataset.inactive = 'true';


         SignHandler.delete_sign_btn.dataset.inactive = 'true';
         SignHandler.create_sign_btn.dataset.inactive = 'false';
         SignHandler.upload_sign_btn.dataset.inactive = 'false';

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

            switch (validate_response.result) {

               case 9:
                  let file = document.querySelector(`.files__item[data-id='${SignHandler.id_file}']`);
                  file.dataset.id_sign = SignHandler.id_sign;
                  file.dataset.validate_results = JSON.stringify(validate_response.validate_results);

                  let results = JSON.parse(file.dataset.validate_results);
                  results.forEach(result => {
                     SignHandler.handleValidateResults(result, file);
                  });

                  SignHandler.file_element.dataset.id_sign = SignHandler.id_sign;

                  SignHandler.certs.dataset.inactive = 'true';
                  SignHandler.delete_sign_btn.dataset.inactive = 'false';


                  FileNeeds.putSignToSave(
                     SignHandler.id_sign,
                     SignHandler.mapping_level_1,
                     SignHandler.mapping_level_2
                  );


                  console.log(FileNeeds.file_needs);

                  break;

               case 6.1:
                  console.log(validate_response.error_message);
                  //TODO error alert
                  break;


            }



         })
         .catch(exception => {
            console.log(exception);
         });
   }

   static handleValidateResults(validate_result, file) {

      //TODO сделать в цикле


      if (validate_result.signature_verify.result && validate_result.certificate_verify.result) {
         file.dataset.sign_state = 'valid';
      } else if (validate_result.signature_verify.result) {
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

         SignHandler.create_sign_btn.dataset.inactive = 'false';
         SignHandler.upload_sign_btn.dataset.inactive = 'false';
         SignHandler.validate_info.dataset.inactive = 'true';

      } else if (file.dataset.is_internal_sign !== 'true') {


         SignHandler.delete_sign_btn.dataset.inactive = 'false';

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
      SignHandler.file_element = file;
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


      SignHandler.create_sign_btn.dataset.inactive = 'true';
      SignHandler.upload_sign_btn.dataset.inactive = 'true';
      SignHandler.delete_sign_btn.dataset.inactive = 'true';



      SignHandler.certs.dataset.inactive = 'true';
      SignHandler.plugin_info.dataset.inactive = 'true';
      SignHandler.actions.dataset.inactive = 'true';
      SignHandler.validate_info.dataset.inactive = 'true';
   }




}