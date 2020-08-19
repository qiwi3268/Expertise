document.addEventListener('DOMContentLoaded', () => {

});

class Sign_Handler {

   static instance;

   modal;
   overlay;

   is_plugin_initialized = false;

   plugin_info;
   validate_info;
   cert_select;
   certs;

   create_sign_btn;
   upload_sign_btn;
   delete_sign_btn;
   sign_btn;
   cancel_btn;
   actions;

   external_sign_input;

   file_element;
   id_file;
   id_sign;

   fs_name_data;
   fs_name_sign;
   file_name;
   mapping_level_1;
   mapping_level_2;

   static getInstance() {

      if (!Sign_Handler.instance) {
         Sign_Handler.instance = new Sign_Handler();
      }

      return Sign_Handler.instance;
   }



   constructor() {

      this.modal = mQS(document, '.sign-modal', 12);
      this.plugin_info = mQS(this.modal, '.sign-modal__cert-info', 13);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);
      this.certs = mQS(this.modal, '.sign-modal__certs', 15);
      this.actions = mQS(this.modal, '.sign-modal__actions', 16);

      this.handleOverlay();
      this.handleCreateSignButton();
      this.handleUploadSignButton();
      this.handleDeleteSignButton();

      this.handleCancelButton();
      this.handleSignButton();

   }

   handleOverlay() {
      this.overlay = mQS(document, '.sign-overlay', 17);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   closeModal() {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

      this.create_sign_btn.dataset.inactive = 'true';
      this.upload_sign_btn.dataset.inactive = 'true';
      this.delete_sign_btn.dataset.inactive = 'true';

      this.closeInfoBlocks();
   }

   closeInfoBlocks() {
      this.certs.dataset.inactive = 'true';
      this.plugin_info.dataset.inactive = 'true';
      this.actions.dataset.inactive = 'true';
      this.validate_info.dataset.inactive = 'true';
   }

   handleCreateSignButton() {
      this.create_sign_btn = document.getElementById('sign_create');
      this.create_sign_btn.addEventListener('click', () => {

         if (!this.is_plugin_initialized && BrowserHelper.checkBrowser()) {
            this.initializePlugin();
         }

      });

   }

   initializePlugin() {

      GeCades.getCadesPlugin()
         .then(() => {

            return GeCades.getPluginData();

         })
         .then(plugin_data => {

            this.putPluginData(plugin_data);
            return GeCades.getCerts();

         })

         .then(certs => {
            this.handleCertListSelect(certs);
            this.showCreateSignElements();
            this.is_plugin_initialized = true;

         })
         .catch(exc => {
            console.log('Ошибка инициализации плагина и зполнения списка сертификатов:');
            console.log(exc);
            this.cancelPluginInitialization();
         });


   }

   putPluginData(plugin_data) {
      document.getElementById('plugin_version').innerHTML = plugin_data.plugin_version;
      document.getElementById('csp_version').innerHTML = plugin_data.csp_version;
   }

   handleCertListSelect(certs) {
      this.certs_select = document.getElementById('cert_list_select');

      this.fillCertListSelect(certs);
      GeCades.setCertificatesList(this.certs_select);

      this.certs_select.addEventListener('change', () => {

         GeCades.getCertInfo()
            .then(cert_info => {
               this.fillCertInfo(cert_info);
            })
            .catch(exc => {
               console.log('Ошибка при получении информации о сертификате: ' + exc);
            });
      });
   }

   fillCertListSelect(certs) {
      certs.forEach(cert => {
         let option = document.createElement('option');
         option.text = cert.text;
         option.value = cert.value;
         option.classList.add('sign-modal__cert');
         this.certs_select.options.add(option);
      });

   }

   showCreateSignElements() {
      this.certs.dataset.inactive = 'false';
      this.plugin_info.dataset.inactive = 'false';

      this.actions.dataset.inactive = 'false';

      this.upload_sign_btn.dataset.inactive = 'true';
      this.create_sign_btn.dataset.inactive = 'true';
   }

   cancelPluginInitialization() {
      this.is_plugin_initialized = false;
      this.closeModal();
   }


   fillCertInfo(cert_info) {
      document.getElementById('subject_name').innerHTML = cert_info.subject_name;
      document.getElementById('issuer_name').innerHTML = cert_info.issuer_name;
      document.getElementById('valid_from_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_from_date);
      document.getElementById('valid_to_date').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(cert_info.valid_to_date);
      document.getElementById('cert_message').innerHTML = cert_info.cert_message;
      document.getElementById('cert_message').style.color = cert_info.cert_status ? '#6cb37e' : '#db5151';
   }



   handleUploadSignButton() {
      this.upload_sign_btn = document.getElementById('sign_upload');
      this.upload_sign_btn.addEventListener('click', () => {
         this.external_sign_input.click();
      });

      this.external_sign_input = document.getElementById('external_sign');
      this.external_sign_input.addEventListener('change', () => {

         if (this.external_sign_input.files.length > 0) {
            let sign_files = Array.from(this.external_sign_input.files);
            this.sendSigns(sign_files);
         }

      });

   }

   sendSigns(sign_files) {

      uploadFiles(sign_files, this.mapping_level_1, this.mapping_level_2)
         .then(uploaded_signs => {

            this.id_sign = uploaded_signs[0].id;
            return checkFile(this.id_file, this.mapping_level_1, this.mapping_level_2);

         })
         .then(file_check_response => {

            this.fs_name_data = file_check_response.fs_name;
            return checkFile(this.id_sign, this.mapping_level_1, this.mapping_level_2);

         })
         .then(sign_check_response => {
            this.fs_name_sign = sign_check_response.fs_name;

            return externalSignatureVerify(
               this.fs_name_data,
               this.fs_name_sign,
               this.mapping_level_1,
               this.mapping_level_2
            );

         })
         .then(validate_results => {

            this.handleValidateResults(validate_results)

         })
         .catch(exc => {
            console.error('Ошибка при загрузке файла открепленной подписи: ' + exc);
         });


   }

   handleValidateResults(validate_results) {
      let results_json = JSON.stringify(validate_results);

      this.file_element.dataset.id_sign = this.id_sign;
      this.file_element.dataset.validate_results = results_json;

      FileNeeds.putSignToSave(this.id_sign, this.mapping_level_1, this.mapping_level_2);

      this.certs.dataset.inactive = 'true';
      this.delete_sign_btn.dataset.inactive = 'false';

      this.fillSignsInfo(results_json);
   }

   fillSignsInfo(validate_results_json) {
      this.validate_info.dataset.inactive = 'false';
      this.validate_info.innerHTML = '';

      let results = JSON.parse(validate_results_json);
      results.forEach(result => {
         this.validate_info.appendChild(this.createSignInfo(result));
      });
   }

   createSignInfo(result) {
      let sign = document.createElement('DIV');
      sign.classList.add('sign-modal__sign');

      let cert_state = result.certificate_verify;
      let cert_row = this.createInfoRow('Сертификат: ', cert_state.user_message, cert_state.result);

      let sign_state = result.signature_verify;
      let sign_row = this.createInfoRow('Подпись: ', sign_state.user_message, cert_state.result);

      let name_row = this.createInfoRow('Подписант: ', result.fio);
      let info_row = this.createInfoRow('Информация: ', result.certificate);

      sign.appendChild(cert_row);
      sign.appendChild(sign_row);
      sign.appendChild(name_row);
      sign.appendChild(info_row);

      return sign;
   }

   createInfoRow(label, text, state) {
      let row = document.createElement('DIV');
      row.classList.add('sign-modal__row');

      let label_span = document.createElement('SPAN');
      label_span.classList.add('sign-modal__label');
      label_span.innerHTML = label;

      let text_span = document.createElement('SPAN');
      text_span.classList.add('sign-modal__text');
      text_span.innerHTML = text;
      if (state !== undefined) {
         text_span.dataset.state = state;
      }

      row.appendChild(label_span);
      row.appendChild(text_span);

      return row;
   }

   handleDeleteSignButton() {
      this.delete_sign_btn = document.getElementById('signature_delete');

   }

   handleCancelButton() {
      this.upload_sign_btn.dataset.inactive = 'false';
      this.create_sign_btn.dataset.inactive = 'false';

      this.closeInfoBlocks();
   }

   handleSignButton() {
      this.sign_btn = document.getElementById('signature_button');
      this.sign_btn.addEventListener('click', () => {
         this.createSign();
      });

   }

   createSign() {
      let selected_algorithm;

      checkFile(this.id_file, this.mapping_level_1, this.mapping_level_2)
         .then(file_check_response => {
            this.fs_name_data = file_check_response.fs_name;
            this.file_name = file_check_response.file_name;

            return GeCades.getSelectedCertificateAlgorithm();
         })
         .then(algorithm => {
            selected_algorithm = algorithm;
            return getFileHash(algorithm, this.fs_name_data);
         })
         .then(file_hash => {
            return GeCades.SignHash_Async(selected_algorithm, file_hash);

         })
         .then(sign_hash => {
            let sign_blob = new Blob([sign_hash], {type: 'text/plain'});
            let file = new File([sign_blob], `${this.file_name}.sig`);

            this.sendSigns([file]);
         });

   }

   open(file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.putFileData(file);
      this.addFileElement(file);

      if (file.dataset.validate_results) {
         this.fillSignsInfo(file.dataset.validate_results);
      }

   }

   putFileData(file) {
      let parent_field = file.closest('[data-mapping_level_1]');
      this.file_element = file;
      this.id_file = file.dataset.id;
      this.mapping_level_1 = parent_field.dataset.mapping_level_1;
      this.mapping_level_2 = parent_field.dataset.mapping_level_2;
   }

   addFileElement(file) {
      let file_info = file.querySelector('.files__info');
      let sign_file = this.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }
}