class PageUtils {
   uri;
   path;
   search_params;

   static get instance() {
      return this._instance;
   }
   static set instance(instance) {
      this._instance = instance;
   }

   static getInstance () {

      if (!this.instance) {
         this.instance = new PageUtils();
      }

      return this.instance;
   }

   constructor () {
      this.path = window.location.pathname;
      this.search_params = new URLSearchParams(window.location.href);
      this.uri = this.initURI();
   }

   initURI () {
      let id_document;
      if (this.search_params.has('id_document')) {
         id_document = this.search_params.get('id_document');
      } else {
         let id_input = document.getElementById('id_document');
         if (!id_input) {
            ErrorModal.open(
               'Ошибка при получении параметра страницы',
               'Не найден параметр id_document'
            );
            return null;
         }

         id_document = id_input.value;
      }

      return `${this.path}?id_document=${id_document}`;
   }

   static getURI () {
      return PageUtils.getInstance().uri;
   }

}