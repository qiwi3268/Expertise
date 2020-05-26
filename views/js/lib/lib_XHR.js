

// method  - post/get
// url     - адрес запроса
// body    - объект key-value, преобразуемый в параметры post-запроса
//	       - null
//		   - объект класса FormData
// headers - объект header-value, преобразуемый в заголовки запроса
//	       - null
// responseType - строка с типом получаемого ответа. По умолчанию - строка
// timeout - количество мс ожидания ответа от сервера (0 - бесконечное)
// uploadProgressCallback - callback для обработки запроса во время отправки данных на сервер

// Карта функции:
//	GET  - передает объект
//	POST - перадает объект
//		   передает форму
//		   передает json
//
function XHR(method, url, body = null, headers = null, responseType = null, timeout = 50000, uploadProgressCallback = null){

    return new Promise((resolve, reject) => {

        //Формируем body запроса:
        //POST: Объект формы передается сам по себе
        //		Объект, где в headers указан json преобразовывается к json
        //		Обычный объект переформировывается в body запроса
        //GET:  Обычный объект переформировывается в body запроса и добавляется к url

        //Есть объект body
        if(body && typeof body === 'object'){

            //POST
            if(method.toLowerCase() === 'post'){

                //Не объект формы
                if(!(body instanceof FormData)){

                    //body - json либо обычный объект
                    body = checkJSON(headers) ? JSON.stringify(body) : createBody(body);
                }

            //GET
            }else if(method.toLowerCase() === 'get'){

                //Приклеиваем body к url
                url += '?' + createBody(body);
                body = null;
            }else{

                console.error('Передан неопределенный method в XHR: ' + method);
                return;
            }
        }

        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);


        if(responseType && typeof responseType === 'string'){
            xhr.responseType = responseType;
        }

        if(timeout && typeof timeout === 'number'){
            xhr.timeout = timeout;
        }

        if(headers && typeof headers === 'object'){
            Object.keys(headers).forEach(key => {
                xhr.setRequestHeader(key, headers[key]);
            });
        }

        xhr.onload = function(){

            if(this.status == 200){
                resolve(this.response);
            }else{
                reject({
                    status: this.status,
                    statusText: this.statusText
                });
            }
        }

        xhr.onerror = function(){
            reject(this.response);
        }

        xhr.ontimeout = function(){
            reject('timeout error. xhr.timeout = '+this.timeout);
        }


        if(uploadProgressCallback && typeof	uploadProgressCallback === 'function'){
            xhr.upload.onprogress = uploadProgressCallback;
        }

        xhr.send(body);
    });
}

function createBody(body){
    return Object.keys(body).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(body[key])).join('&');
}

function checkJSON(headers){
    if(headers && typeof headers === 'object'){
        for(let key in headers){
            if(~headers[key].toLowerCase().indexOf('json')){
                return true;
            }
        }
    }
    return false;
}

/*
//Пример

let body = {
	'email': 'vam@ge74.ru',
	'login': 'test'
};

let header = {
	'Content-Type' : 'application/x-www-form-urlencoded; charset=utf-8'
}

XHR('post', '/API_EmailUniqueness', body, header, 'json')
.then(response => {
	console.log('response: ', response);
})
.catch(err => {
  console.error('XHR error: ', err);
});

*/