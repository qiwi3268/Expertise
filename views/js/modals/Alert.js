class Alert {

   modal;
   overlay;

   confirm_button;
   cancel_button;

   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   static getInstance () {
      if (!this.instance) {
         this.instance = new CommentCreator();
      }
      return this.instance;
   }

   static show (action_name) {
      let alert = this.getInstance();
      alert.modal.classList.add('active');
      alert.overlay.classList.add('active');
   }



   constructor () {
      this.modal = document.getElementById('alert_modal');
      this.overlay = document.getElementById('alert_overlay');


      this.confirm_button = document.getElementById('alert_confirm');
      this.confirm_button.addEventListener('click', () => this.confirm());

      this.cancel_button = document.getElementById('alert_cancel');
      this.cancel_button.addEventListener('click', () => this.cancel());
   }

   confirm () {
      switch (this.confirm_button.dataset.action) {
         default :
            this.close();
      }
   }

   cancel () {
      switch (this.cancel_button.dataset.action) {
         case 'navigation':
            window.location.href = '/home/navigation';
            break;
         default :
            this.close();
      }
   }

   close () {
      this.modal.classList.remove('active');
      this.overlay.classList.remove('active');
   }

}




