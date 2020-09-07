class SignView {

   validate_info;


   static getInstance () {

      if (!this.instance) {
         this.instance = new SignView();
      }

      return this.instance;
   }

   constructor () {
      this.modal = mQS(document, '.sign-modal', 12);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);

      this.handleOverlay();

   }

   // Предназначен для обработки нажатия на фон модального окна
   handleOverlay () {
      this.overlay = mQS(document, '.sign-overlay', 17);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   // Предназначен для закрытия модуля подписания
   closeModal () {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
      this.validate_info.dataset.active = 'false';

   }

   open (file) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.addFileElement(file);

      if (file.dataset.validate_results) {
         this.fillSignsInfo(file.dataset.validate_results);
      }

   }

   addFileElement (file) {
      let file_info = file.querySelector('.files__info');
      let sign_file = this.modal.querySelector('.sign-modal__file');
      sign_file.innerHTML = file_info.innerHTML;
   }

   fillSignsInfo (validate_results_json) {
      this.validate_info.dataset.active = 'true';
      this.validate_info.innerHTML = '';

      let results = JSON.parse(validate_results_json);
      results.forEach(result => {
         this.validate_info.appendChild(this.createSignInfo(result));
      });
   }

   createSignInfo (result) {
      let sign = document.createElement('DIV');
      sign.classList.add('sign-modal__sign');

      let cert_state = result.certificate_verify;
      let cert_row = this.createInfoRow('Сертификат: ', cert_state.user_message, cert_state.result);

      let sign_state = result.signature_verify;
      let sign_row = this.createInfoRow('Подпись: ', sign_state.user_message, sign_state.result);

      let name_row = this.createInfoRow('Подписант: ', result.fio);
      let info_row = this.createInfoRow('Информация: ', result.certificate);

      sign.appendChild(cert_row);
      sign.appendChild(sign_row);
      sign.appendChild(name_row);
      sign.appendChild(info_row);

      return sign;
   }

   createInfoRow (label, text, state = null) {
      let row = document.createElement('DIV');
      row.classList.add('sign-modal__sign-row');

      let label_span = document.createElement('SPAN');
      label_span.classList.add('sign-modal__label');
      label_span.innerHTML = label;

      let text_span = document.createElement('SPAN');
      text_span.classList.add('sign-modal__text');
      text_span.innerHTML = text;
      //asd
      if (state !== null) {
         text_span.dataset.state = state;
      }

      row.appendChild(label_span);
      row.appendChild(text_span);

      return row;
   }

   // Предназначен для отображения состояния проверки подписи в поле с файлом
   // Принимает параметры-------------------------------
   // file         Element : проверяемый файл
   static validateFileField (file) {
      let results_json = file.dataset.validate_results;
      let sign_state = 'not_signed';

      if (results_json) {
         let results = JSON.parse(results_json);

         for (let result of results) {
            if (result.signature_verify.result && result.certificate_verify.result) {
               sign_state = 'valid';
            } else if (result.signature_verify.result) {
               sign_state = 'warning';
               break;
            } else {
               break;
            }
         }

      }
      GeFile.setSignState(file, sign_state);

   }
}

