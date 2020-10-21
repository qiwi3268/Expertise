
class CommentGroup {
   modal;
   overlay;

   static get instance() {
      return this._instance;
   }

   static set instance(instance) {
      this._instance = instance;
   }

   static getInstance () {

      if (!this.instance) {
         this.instance = new CommentGroup();
      }

      return this.instance;
   }

   constructor () {
      this.modal = document.getElementById('group_modal');
      this.overlay = document.getElementById('group_overlay');

      this.overlay.addEventListener('click', () => {
         this.modal.classList.remove('active');
         this.overlay.classList.remove('active');
      });

   }

   open () {
      this.modal.classList.add('active');
      this.overlay.classList.add('active');
   }

}