document.addEventListener('DOMContentLoaded', () => {

    // этот файл можешь привести к форматированию кода по своему типу

    let form = document.querySelector('#form');
    let progress_bar = document.querySelector('#progress_bar');

    let request_urn = '/API_file_uploader';

    // когда идет загрузка файла на серер нужно запрещать клик по кнопке "загрузить файл"
    form.addEventListener('submit', event => {

        event.preventDefault();

        // тут делаешь все требуемые проверки, и если не удовлетворяет - сообщение и выход из функции
        // а еще лучше проверки делать в момент попадания файлов в input type="file", и нажатие на отправку
        // формы запрещать, если проверки не пройдены

        XHR('post', request_urn, new FormData(form), null, 'json', null, uploadProgressCallback)
            .then(response => {



            })
            .catch(error => {

                // p.s. все сообщения об ошибках везде делаем однотипными
                console.error('XHR error: ', error);
                // Ошибка XHR запроса. Обратитесь к администратору
            });
    });

    function uploadProgressCallback(event){
        let download_percent = Math.round(100 * event.loaded / event.total);
        progress_bar.textContent = download_percent + ' %';
    }






    // файл чекер
    let form_check = document.querySelector('#form_check');

    form_check.addEventListener('submit', event => {

        event.preventDefault();

        XHR('post', '/API_file_checker', new FormData(form_check), null, 'json')
            .then(response => {

                console.log(response)

            })
            .catch(error => {
                console.error('XHR error: ', error);
            });
    });


});