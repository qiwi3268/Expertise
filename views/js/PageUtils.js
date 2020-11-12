class PageUtils {

   static getURI () {
      let path = window.location.pathname;
      let url = new URL(window.location.href);
      let search = url.searchParams;

      let id_document;
      if (search.has('id_document')) {
         id_document = search.get('id_document');
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

      return `${path}?id_document=${id_document}`;
   }

}