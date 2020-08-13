document.addEventListener('DOMContentLoaded', () => {


    let signatureButton = document.getElementById('signature_button');

    let signatureFile = document.getElementById('signature_file');

    signatureButton.addEventListener('click',() => {

        // Проверка на выбранный файл
        if(signatureFile.files.length == 0){
            //alert('Необходимо выбрать файл');
            //return;
        }
        // TODO - Не вызывать функцию, если выбран корявый сертификат
        //

        if(!BrowserPropertiesHelper.canFileApi()){
            console.log('Браузер не соответствует требованиям АИС (File Api не поддерживается)');
            return;
        }

        let oFile = signatureFile.files[0];

        let oFReader = new FileReader();

        oFReader.readAsDataURL(oFile);

        oFReader.onload = function(FREvent){
            let header = ";base64,";
            let fileData = FREvent.target.result;
            let base64Data = fileData.substr(fileData.indexOf(header) + header.length);

            GeCades.GetHashedData_Async(base64Data)
                .then(oHashedData => {

                    GeCades.SignHash_Async(oHashedData)
                        .then(Signature => {

                            console.log(Signature);

                            GeCades.VerifySignature_Async(oHashedData, Signature)
                                .then(result => {

                                    console.log('Подпись успешно проверена');
                                })
                                .catch(ex => {
                                    console.log(ex);
                                });
                        }).catch(ex => {
                            console.log('Ошибка в работе метода SignHash_Async: ' + ex);
                        });
                })
                .catch(ex => {
                    console.log('Ошибка в работе метода GetHashedData_Async: ' + ex);
                });
        };
    });


    // Блок проверок на непподерживаемые браузеры
    if(BrowserPropertiesHelper.isInternetExplorer()){
        console.log('Браузер не соответствует требованиям АИС (Internet Explorer не поддерживается)');
        return;
    }else if(isEdge()){

        console.log('Браузер не соответствует требованиям АИС (Edge не поддерживается)');
        return;
    }else if(!BrowserPropertiesHelper.canPromise()){

        console.log('Браузер не соответствует требованиям АИС (отсутствует поддержка promise)');
        return;
    }else{

        cadesplugin
            .then(() => {

                let canAsync = !!cadesplugin.CreateObjectAsync;
                if(canAsync){

                    GeCades.CheckForPlugIn_Async('PlugInVersionTxt', 'CSPVersionTxt')

                    GeCades.FillCertList_Async('CertListBox');

                }else{
                    console.log('Браузер не соответствует требованиям АИС (отсутствует поддержка async)');
                }
            })
            .catch(ex => {
                console.log('Ошибка при инициализации cadesplugin:' + ex);
            });
    }


    //--------------------------проверка встроенной подписи
    let internalSignatureButton = document.getElementById('internal_signature_button');

    let internalSignatureFile = document.getElementById('internal_signature_file');

    internalSignatureButton.addEventListener('click',() => {


        let oFile = internalSignatureFile.files[0];

        let oFReader = new FileReader();

        oFReader.readAsText(oFile);

        oFReader.onload = function(FREvent){

            let fileData = FREvent.target.result;

            //console.log(fileData);

            //GeCades.VerifyInternalSignature_Async(fileData);
        }
    });


});




// Проверка на браузер IE
function isIE(){
    let retVal = (("Microsoft Internet Explorer" == navigator.appName) || // IE < 11
        navigator.userAgent.match(/Trident\/./i)); // IE 11
    return retVal;
}

// Проверка на браузер Edge
function isEdge(){
    let retVal = navigator.userAgent.match(/Edge\/./i);
    return retVal;
}