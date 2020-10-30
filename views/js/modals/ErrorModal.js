document.addEventListener('DOMContentLoaded', () => {
   ErrorModal.init();

});

class ErrorModal {
   // _modal;
   // overlay;

   // title;
   // message;

   static get modal() {
      return this._modal;
   }

   static set modal(modal) {
      this._modal = modal;
   }

   static get overlay() {
      return this._overlay;
   }

   static set overlay(overlay) {
      this._overlay = overlay;
   }

   static get title() {
      return this._title;
   }

   static set title(title) {
      this._title = title;
   }

   static get message() {
      return this._message;
   }

   static set message(message) {
      this._message = message;
   }

   static init () {
      this.modal = document.querySelector('.error-modal');

      this.overlay = document.querySelector('.error-overlay');
      this.overlay.addEventListener('click', this.close);

      let close_button = document.createElement('I');
      close_button.classList.add('modal__close', 'fas', 'fa-times');
      // close_button.classList.add('active');
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

