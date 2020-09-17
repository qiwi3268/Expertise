exceptions = {
   1: {description: ``},
   2: {description: ``},


};

document.addEventListener('DOMContentLoaded', () => {
   ErrorModal.init();
});

class ErrorModal {
   static modal;
   static overlay;

   static title;
   static message;

   static init () {
      this.modal = document.querySelector('.error-modal');

      this.overlay = document.querySelector('.error-overlay');
      this.overlay.addEventListener('click', this.close);

      let close_button = document.createElement('I');
      close_button.classList.add('modal__close', 'fas', 'fa-times');
      close_button.classList.add('active');
      this.modal.appendChild(close_button);
      close_button.addEventListener('click', this.close);

      this.title = this.modal.querySelector('.error-modal__title');
      this.message = this.modal.querySelector('.error-modal__message');

   }

   static close () {
      ErrorModal.modal.classList.remove('active');
      ErrorModal.overlay.classList.remove('active');
   }

   static open (title, message) {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');

      this.title.innerHTML = title;
      this.message.innerHTML = message;

   }

}





















