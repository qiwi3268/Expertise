document.addEventListener('DOMContentLoaded', () => {
    let save_button = document.querySelector('#application_save');

    let form = document.querySelector('#application');
    let request_urn = '/home/API_save_application';

    save_button.addEventListener('click', () => {


        XHR('post', request_urn, new FormData(form), null, 'json', null, null)
            .then(response => {
                alert(response.result);


            })
            .catch(error => {
                alert(error.result);
                alert(error.message);
                console.error('XHR error: ', error);
            });

    });



});