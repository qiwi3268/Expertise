// Вспомогательный класс для определения поддерживаемые свойств текущего браузера
//
class BrowserHelper{



   static checkBrowser() {
      //TODO alert

      // Блок проверок на непподерживаемые браузеры
      if(BrowserHelper.isInternetExplorer()){
         console.log('Браузер не соответствует требованиям АИС (Internet Explorer не поддерживается)');
         return false;
      } else if(BrowserHelper.isEdge()) {
         console.log('Браузер не соответствует требованиям АИС (Edge не поддерживается)');
         return false;
      } else if(!BrowserHelper.canPromise()) {
         console.log('Браузер не соответствует требованиям АИС (отсутствует поддержка promise)');
         return false;
      }

      return true;
   }

   // Проверка браузера на наличие promise
   static canPromise(){
      return !!window.Promise;
   }

   // Проверка браузера на наличие File Api
   static canFileApi(){
      if(!!window.FileReader){
         let fileReader = new FileReader();
         if(typeof(fileReader.readAsDataURL) == 'function'){
            return true;
         }
      }
      return false;
   }

   // Проверка на браузер IE
   static isInternetExplorer(){
      let retVal = (("Microsoft Internet Explorer" == navigator.appName) || // IE < 11
         navigator.userAgent.match(/Trident\/./i)); // IE 11
      return retVal;
   };

   // Проверка на браузер Edge
   static isEdge(){
      let retVal = navigator.userAgent.match(/Edge\/./i);
      return retVal;
   }


}