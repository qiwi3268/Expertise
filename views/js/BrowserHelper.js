// Вспомогательный класс для определения поддерживаемые свойств текущего браузера
//
class BrowserHelper {


   static checkBrowser () {

      // Блок проверок на непподерживаемые браузеры
      if (BrowserHelper.isInternetExplorer()) {
         ErrorModal.open('Браузер не соответствует требованиям АИС', 'Internet Explorer не поддерживается');
         return false;
      } else if (BrowserHelper.isEdge()) {
         ErrorModal.open('Браузер не соответствует требованиям АИС', 'Edge не поддерживается');
         return false;
      } else if (!BrowserHelper.canPromise()) {
         ErrorModal.open('Браузер не соответствует требованиям АИС', 'Отсутствует поддержка promise');
         return false;
      }

      return true;
   }

   // Проверка браузера на наличие promise
   static canPromise () {
      return !!window.Promise;
   }

   // Проверка браузера на наличие File Api
   static canFileApi () {
      if (!!window.FileReader) {
         let fileReader = new FileReader();
         if (typeof (fileReader.readAsDataURL) == 'function') {
            return true;
         }
      }
      return false;
   }

   // Проверка на браузер IE
   static isInternetExplorer () {
      return (
         ("Microsoft Internet Explorer" == navigator.appName)
         || navigator.userAgent.match(/Trident\/./i)
      );
   };

   // Проверка на браузер Edge
   static isEdge () {
      return navigator.userAgent.match(/Edge\/./i);
   }


}