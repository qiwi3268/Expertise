document.addEventListener('DOMContentLoaded', () => {
   //TODO сделать синглтоном
   SignHandler.init();

});

class Sign_Handler {

   static instance;

   modal;
   overlay;

   plugin_info;
   validate_info;
   cert_select;

   create_sign_btn;
   upload_sign_btn;
   delete_sign_btn;
   actions;

   file_element;
   id_file;
   id_sign;

   external_sign_input;

   fs_name_data;
   fs_name_sign;
   file_name;
   mapping_level_1;
   mapping_level_2;

   constructor() {

      this.modal = mQS(document, '.sign-modal', 12);
      this.plugin_info = mQS(this.modal, '.sign-modal__info', 13);
      this.validate_info = mQS(this.modal, '.sign-modal__validate', 14);
      this.cert_select = mQS(this.modal, '.sign-modal__certs', 15);

      this.handleOverlay();
      this.handleCreateSignButton();
      this.handleUploadSignButton();
      this.handleDeleteSignButton();

   }

   handleOverlay() {
      this.overlay = mQS(document, '.sign-overlay', 16);
      this.overlay.addEventListener('click', () => this.closeModal());
   }

   closeModal() {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');

      this.create_sign_btn.dataset.inactive = 'true';
      this.upload_sign_btn.dataset.inactive = 'true';
      this.delete_sign_btn.dataset.inactive = 'true';

      this.cert_select.dataset.inactive = 'true';
      this.actions.dataset.inactive = 'true';
      this.validate_info.dataset.inactive = 'true';
   }

   handleCreateSignButton() {
      this.create_sign_btn = document.getElementById('sign_create');
      this.create_sign_btn.addEventListener('click', () => {

      });

   }

   handleUploadSignButton() {

   }

   handleDeleteSignButton() {

   }
}