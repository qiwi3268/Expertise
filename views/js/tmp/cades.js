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

            GeCades.VerifyInternalSignature_Async(fileData);
        }
    });


});





/*


// Работа над подписанием файла
function SignCadesBES_Async_File(certListBoxId, dataToSign){
    cadesplugin.async_spawn(function*(arg){

        let select = document.getElementById(arg[0]);
        let selectedCertID = select.selectedIndex;

        // TODO - Переместить это условие на клик по кнопке "Подписать"
        if(selectedCertID == -1){
            alert("Необходимо выбрать сертификат");
            return;
        }

        let thumbprint = select.options[selectedCertID].value;
        let oSertificate = GlobalCertsMap.get(thumbprint);


        let Signature;
        try{

            let oSigner;

            try{

                oSigner = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");
            }catch(ex){
                throw 'Ошибка при создании объекта CPSigner: ' + ex.number;
            }


            // Атрибуты усовершенствованной подписи
            let oSigningTimeAttr = yield cadesplugin.CreateObjectAsync("CADESCOM.CPAttribute");

            yield oSigningTimeAttr.propset_Name(cadesplugin.CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME);
            let oTimeNow = new Date();
            yield oSigningTimeAttr.propset_Value(oTimeNow);
            let attr = yield oSigner.AuthenticatedAttributes2;
            yield attr.Add(oSigningTimeAttr);


            let oDocumentNameAttr = yield cadesplugin.CreateObjectAsync("CADESCOM.CPAttribute");
            yield oDocumentNameAttr.propset_Name(cadesplugin.CADESCOM_AUTHENTICATED_ATTRIBUTE_DOCUMENT_NAME);
            yield oDocumentNameAttr.propset_Value("Document Name");
            yield attr.Add(oDocumentNameAttr);

            if(oSigner){
                yield oSigner.propset_Certificate(oSertificate);
            }else{
                throw 'Ошибка при создании объекта CPSigner';
            }

            let oSignedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");

            let dataToSign = arg[1];

            if(dataToSign){

                yield oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
                yield oSignedData.propset_Content(dataToSign);
                yield oSigner.propset_Options(cadesplugin.CAPICOM_CERTIFICATE_INCLUDE_WHOLE_CHAIN);

                try{
                    Signature = yield oSignedData.SignCades(oSigner, cadesplugin.CADESCOM_CADES_BES, true);
                }catch(ex){
                    throw "Не удалось создать подпись из-за ошибки: " + cadesplugin.getLastError(ex);
                }
            }
            console.log('all-good');
            console.log(Signature);

        }catch(ex){
            console.log(ex);
        }


    }, certListBoxId, dataToSign);
}
*/




function GetHashData(sBase64Data){
    cadesplugin.async_spawn (function*(arg){

        let PublicKey = yield SelectedCetrificate.PublicKey();

        console.log(PublicKey);

        let Algorithm = yield PublicKey.Algorithm;
        let AlgorithmValue = yield Algorithm.Value;

        // TODO проверку на не null
        let alg = GetAlgorithmByValue(AlgorithmValue);

        console.log(alg);

        // Создаем объект CAdESCOM.HashedData
        let oHashedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.HashedData");

        yield oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
        // Алгоритм хэширования нужно указать до того, как будут переданы данные
        yield oHashedData.propset_Algorithm(alg);


        let str = arg[0];
        let length = str.length;
        let chunkSize = 100000;

        while(length > chunkSize){

            yield oHashedData.Hash(str.substring(0, chunkSize));
            str = str.substring(chunkSize, str.length);
            length -= chunkSize;
        }
        yield oHashedData.Hash(str);


        //yield oHashedData.Hash(str);

        //let HashValue = yield oHashedData.Value;



        let oSigner = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");

        // Атрибуты усовершенствованной подписи
        let oSigningTimeAttr = yield cadesplugin.CreateObjectAsync("CADESCOM.CPAttribute");

        yield oSigningTimeAttr.propset_Name(cadesplugin.CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME);
        let oTimeNow = new Date();
        yield oSigningTimeAttr.propset_Value(oTimeNow);
        let attr = yield oSigner.AuthenticatedAttributes2;
        yield attr.Add(oSigningTimeAttr);


        let oDocumentNameAttr = yield cadesplugin.CreateObjectAsync("CADESCOM.CPAttribute");
        yield oDocumentNameAttr.propset_Name(cadesplugin.CADESCOM_AUTHENTICATED_ATTRIBUTE_DOCUMENT_NAME);
        yield oDocumentNameAttr.propset_Value("Document Name");
        yield attr.Add(oDocumentNameAttr);


        if(oSigner){
            yield oSigner.propset_Certificate(SelectedCetrificate);
        }else{
            throw 'Ошибка при создании объекта CPSigner';
        }


        let oSignedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");
        yield oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);

        //yield oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
        //yield oSignedData.propset_Content(dataToSign);


        yield oSigner.propset_Options(cadesplugin.CAPICOM_CERTIFICATE_INCLUDE_WHOLE_CHAIN);

        let sSignedMessage = "";

        // Вычисляем значение подписи
        try {
            sSignedMessage = yield oSignedData.SignHash(oHashedData, oSigner, cadesplugin.CADESCOM_CADES_BES);
        } catch (err) {
            alert("Failed to create signature. Error: " + cadesplugin.getLastError(err));
            return;
        }


        console.log(sSignedMessage);






    }, sBase64Data);
}



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