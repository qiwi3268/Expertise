document.addEventListener('DOMContentLoaded', () => {

   let form = document.querySelector('#form');

   let url_login = '/API_login';

   form.addEventListener('submit', event => {

      event.preventDefault();

      XHR('post', url_login, new FormData(form), null, 'json')
         .then(response => {

            if (response === null) {
               ErrorModal.open('Ошибка авторизации', 'Не получен ответ от сервера. Обратитесь к администратору');
               return;
            }

            handleResponse(response);

         })
         .catch(error => {
            ErrorModal.open('Ошибка авторизации', error);
         });

   });

   function handleResponse (response) {
      switch (response.result) {
         case 5 :
            location.href = response.ref;
            break;
         case 2 :
            ErrorModal.open('Ошибка авторизации', 'Неверный логин или пароль');
            break;
         case 3 :
            ErrorModal.open('Ошибка авторизации', 'Учетная запись заблокирована. Обратитесь к администратору');
            break;
         case 4 :
            ErrorModal.open('Ошибка авторизации', 'Учетная запись не назначена на роль в системе. Обратитесь к администратору');
            break;
         case 1 :
            ErrorModal.open('Ошибка авторизации', 'Нет обязательных параметров запроса на сервер. Обратитесь к администратору');
            break;
         case 6 :
            ErrorModal.open('Ошибка авторизации', `Непредвиденная ошибка. code: ${response.code}, message: ${response.message}`);
            break;
         default :
            ErrorModal.open('Ошибка авторизации', `Получен неизвестный ответ от сервера. response.result: ${response.result}`);
            break;
      }
   }

});