document.addEventListener('DOMContentLoaded', () => {

    let form = document.querySelector('#form');
    let error_elem = document.querySelector('#form-login__error');
    let error_text = document.querySelector('#form-login__error-text');

    let url_login = '/API_login';

    let error_message;

    form.addEventListener('submit', event => {

        event.preventDefault();

        XHR('post', url_login, new FormData(form), null, 'json')
            .then(response => {

                if (response === null) {
                    console.error('Не получен ответ от сервера. response: ', response);
                    createErrorNotification('Не получен ответ от сервера. Обратитесь к администратору');
                    return;
                }

                handleResponse(response);
                
            })
            .catch(error => {
                console.error('XHR error: ', error);
                createErrorNotification('Ошибка XHR запроса. Обратитесь к администратору');
            });

    });

    function handleResponse(response) {
        switch (response.result) {
            case 5 :
                location.href = response.ref;
                break;
            case 2 :
                createErrorNotification('Неверный логин или пароль');
                break;
            case 3 :
                createErrorNotification('Учетная запись заблокирована');
                break;
            case 4 :
                createErrorNotification('Учетная запись не назначена на роль в системе. Обратитесь к администратору');
                break;
            case 6 :
                error_message = `Ошибка при запросе к БД. code: ${ response.code }, message: ${ response.message }`;
                console.error(error_message);
                createErrorNotification('Ошибка при запросе к БД. Обратитесь к администратору');
                break;
            case 1 :
                createErrorNotification('Нет обязательных параметров запроса на сервер. Обратитесь к администратору');
                break;
            case 7 :
                error_message = `Непредвиденная ошибка. code: ${ response.code }, message: ${ response.message }`;
                console.error(error_message);
                createErrorNotification('Непредвиденная ошибка. Обратитесь к администратору');
                break;
            default :
                console.error('Получен неизвестный ответ от сервера. response.result: ', response.result);
                createErrorNotification('Получен неизвестный ответ от сервера. Обратитесь к администратору');
                break;
        }
    }

    function createErrorNotification(text) {
        error_elem.style.display = 'block';
        error_text.textContent = text;
    }


});