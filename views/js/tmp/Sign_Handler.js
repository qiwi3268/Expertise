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

   }
}