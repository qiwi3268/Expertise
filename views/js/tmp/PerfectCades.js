

class GeCades{


    static getCadesPlugin() {
        return cadesplugin;
    }

    static getPluginData(){

        return new Promise((resolve, reject) => {

            let canAsync = !!cadesplugin.CreateObjectAsync;
            if (canAsync) {

                cadesplugin.async_spawn(function*(){

                    let oAbout;

                    try {
                        oAbout = yield cadesplugin.CreateObjectAsync("CAdESCOM.About");
                    } catch(exc) {
                        reject("Ошибка при создании объекта About: " + cadesplugin.getLastError(exc));
                    }

                    let CurrentPluginVersion = yield oAbout.PluginVersion;  // Версия плагина
                    let CurrentCSPVersion = yield oAbout.CSPVersion("", 80); // Версия криптопровайдера

                    let CurrentPluginVersion_string = (yield CurrentPluginVersion.toString());
                    let CurrentCPVersion_string = (yield CurrentCSPVersion.MajorVersion) + "." + (yield CurrentCSPVersion.MinorVersion) + "." + (yield CurrentCSPVersion.BuildVersion);


                    let plugin_data = {
                        plugin_version: CurrentPluginVersion_string,
                        csp_version: CurrentCPVersion_string
                    };

                    resolve(plugin_data);
                });

            } else {
                reject('Браузер не соответствует требованиям АИС (отсутствует поддержка async)');
            }

        });
    }


    static getCertStore() {
        return new Promise((resolve, reject) => {

           cadesplugin.async_spawn(function* () {

               try {

                   let o_store = yield cadesplugin.CreateObjectAsync("CAdESCOM.Store");

                   if (!o_store) {
                       reject('Ошибка при создании хранилища сертификатов');
                   }

                   resolve(o_store);

               } catch (exc) {
                   reject('Хранилище сертификатов недоступно ' + cadesplugin.getLastError(exc));
               }
           });

        });

    }

    // TODO добавить коды для ошибок
    static getCerts() {
        return new Promise((resolve, reject) => {

            cadesplugin.async_spawn(function*(){

                let o_store;

                try {

                    o_store = yield cadesplugin.CreateObjectAsync("CAdESCOM.Store");

                    if (!o_store) {
                        reject('Ошибка при создании хранилища сертификатов');
                    }

                    yield o_store.Open();

                } catch (exc) {
                    reject('Хранилище сертификатов недоступно ' + cadesplugin.getLastError(exc));
                }


                let certs;
                let certs_data = [];
                let certs_count;

                try {

                    certs = yield o_store.Certificates;
                    certs_count = yield certs.Count;

                } catch(exc) {
                    reject('Ошибка при получении Certificates или Count: ' + cadesplugin.getLastError(exc));
                }

                // В хранилище отсутствуют сертификаты
                if (certs_count === 0) {
                    //TODO alert
                    reject('Хранилище сертификатов пусто');
                }


                // Перебор сертификатов
                for (let i = 1; i <= certs_count; i++) {

                    let cert;
                    let cert_data = {};

                    try {
                        cert = yield certs.Item(i);
                    } catch(exc) {
                        reject("Ошибка при перечислении сертификатов: " + cadesplugin.getLastError(exc));
                    }

                    let valid_from_date; // Дата выдачи
                    let valid_to_date;   // Срок действия
                    let subject_name;   // Описание сертификата

                    try {

                        valid_from_date = new Date(yield cert.ValidFromDate);
                        valid_to_date = new Date(yield cert.ValidToDate);
                        subject_name = yield cert.SubjectName;

                    } catch(exc) {
                        console.log("Ошибка при получении свойства ValidFromDate / ValidToDate / SubjectName: " + cadesplugin.getLastError(exc));
                        continue;
                    }

                    let hasPrivateKey; // Привязка сертификата к закрытому ключу

                    try {
                        hasPrivateKey = yield cert.HasPrivateKey();
                    } catch(exc) {
                        console.log("Ошибка при получении свойства HasPrivateKey: " + cadesplugin.getLastError(exc));
                    }

                    // Берем только действительные сертификаты и с привязкой к закрытому ключу
                    // if(new Date() < ValidToDate && hasPrivateKey) {
                    if (new Date() < valid_to_date) {

                        cert_data.text = GeCades.extractCN(subject_name) + ' Выдан: ' + GeCades.formattedDateTo_ddmmyyyy(valid_from_date);

                    } else {
                        continue;
                    }

                    try {

                         // Отпечаток подписи
                        let thumbprint = yield cert.Thumbprint;
                        cert_data.value = thumbprint;
                        GeCades.setCertificateToGlobalMap(thumbprint, cert);


                    } catch(exc) {
                        console.log("Ошибка при получении свойства Thumbprint: " + cadesplugin.getLastError(exc));
                        continue;
                    }


                    certs_data.push(cert_data);

                }

                yield o_store.Close();
                resolve(certs_data);

            });

        });
    }


    static FillCertList_Async(selectId){
        cadesplugin.async_spawn(function*(args){

            let oStore; // Хранилище сертификатов

            try{

                oStore = yield cadesplugin.CreateObjectAsync("CAdESCOM.Store");

                SignHandler.showCertBlock();


                if(!oStore){
                    console.log('Ошибка при создании хранилища сертификатов');
                    return;
                }

                yield oStore.Open();

            }catch(ex){

                console.log('Ошибка. Хранилище сертификатов недоступно ' + cadesplugin.getLastError(ex));

                SignHandler.cancelPluginInitialization();
                return;
            }

            // html-контейнер для отображения сертификатов
            GeCades.setCertificatesList(args[0]);
            let select = GeCades.getCertificatesList();


            if(!select){
                console.log('Ошибка. Отсутствует контейнер хранения сертификатов');
                return;
            }


            // Заполнение информации при выборе сертификата
            select.onchange = GeCades.FillCertInfo_Async;


            let certs;
            let certsCnt;

            try{

                certs = yield oStore.Certificates;
                certsCnt = yield certs.Count;


            }catch(ex){

                console.log('Ошибка при получении Certificates или Count: ' + cadesplugin.getLastError(ex));
                return;
            }

            // В хранилище отсутствуют сертификаты
            if(certsCnt == 0){
                console.log('Хранилище сертификатов пусто');
                return;
            }


            // Перебор сертификатов
            for(let i = 1; i <= certsCnt; i++){

                let cert;

                try{
                    cert = yield certs.Item(i);
                }catch(ex){
                    console.log("Ошибка при перечислении сертификатов: " + cadesplugin.getLastError(ex));
                    return;
                }

                let option = document.createElement("option");


                let ValidFromDate; // Дата выдачи
                let ValidToDate;   // Срок действия
                let SubjectName;   // Описание сертификата

                try{

                    ValidFromDate = new Date(yield cert.ValidFromDate);
                    ValidToDate = new Date(yield cert.ValidToDate);
                    SubjectName = yield cert.SubjectName;
                }catch(ex){
                    console.log("Ошибка при получении свойства ValidFromDate / ValidToDate / SubjectName: " + cadesplugin.getLastError(ex));
                }

                let hasPrivateKey; // Привязка сертификата к закрытому ключу

                try{
                    hasPrivateKey = yield cert.HasPrivateKey();
                }catch(ex){
                    console.log("Ошибка при получении свойства HasPrivateKey: " + cadesplugin.getLastError(ex));
                }

                // Берем только действительные сертификаты и с привязкой к закрытому ключу
                // if(new Date() < ValidToDate && hasPrivateKey) {
                if(new Date() < ValidToDate) {

                    let text = GeCades.extractCN(SubjectName) + ' Выдан: ' + GeCades.formattedDateTo_ddmmyyyy(ValidFromDate);
                    option.text = text;

                }else{
                    continue;
                }

                try{


                    let Thumbprint = yield cert.Thumbprint; // Отпечаток подписи
                    option.value = Thumbprint;
                    GeCades.setCertificateToGlobalMap(Thumbprint, cert);



                }catch(ex){
                    console.log("Ошибка при получении свойства Thumbprint: " + cadesplugin.getLastError(ex));
                }


                select.options.add(option);
                option.classList.add('sign-modal__cert');

            }


            yield oStore.Close();

        }, selectId);


    }


    static getCertInfo() {
        return new Promise((resolve) => {

            let o_certificate = GeCades.getSelectedCertificateFromGlobalMap();

            cadesplugin.async_spawn(function*() {

                let cert_info = {};
                let subject_name; // Владелец
                let issuer_name; // Издатель
                let valid_from_date; // Дата выдачи
                let valid_to_date; // Срок действия

                try {

                    subject_name = GeCades.extractCN(yield o_certificate.SubjectName);
                    issuer_name = GeCades.extractCN(yield o_certificate.IssuerName);
                    valid_from_date = new Date(yield o_certificate.ValidFromDate);
                    valid_to_date = new Date(yield o_certificate.ValidToDate);

                } catch(exc) {
                    console.log("Ошибка при получении свойства SubjectName / IssuerName / ValidFromDate / ValidToDate: " + cadesplugin.getLastError(exc));
                }

                let validator;
                let is_valid = undefined; // В случае неизвестного алгоритма

                // Если попадется сертификат с неизвестным алгоритмом, то
                // тут будет исключение. В таком сертификате просто такое поле
                try {
                    validator = yield o_certificate.IsValid();
                    is_valid = yield validator.Result;
                } catch(exc) {

                }

                let has_private_key; // Привязка сертификата к закрытому ключу

                try {
                    has_private_key = yield o_certificate.HasPrivateKey();
                } catch(exc) {
                    console.log("Ошибка при получении свойства HasPrivateKey: " + cadesplugin.getLastError(exc));
                }

                let now = new Date();
                let cert_message;
                let cert_status = false;

                if (now < valid_from_date) {
                    cert_message = 'Срок действия не наступил';
                } else if (now > valid_to_date) {
                    cert_message = 'Срок действия истек';
                } else if (!has_private_key) {
                    cert_message = 'Нет привязки к закрытому ключу';
                } else if (is_valid === false) {
                    cert_message = 'Ошибка при проверке цепочки сертификатов';
                } else if (is_valid === undefined) {
                    cert_message = 'Сертификат с неизвестным алгоритмом';
                } else {
                    cert_message = 'Действителен';
                    cert_status = true;
                }

                cert_info.subject_name = subject_name;
                cert_info.issuer_name = issuer_name;
                cert_info.valid_from_date = valid_from_date;
                cert_info.valid_to_date = valid_to_date;
                cert_info.cert_message = cert_message;
                cert_info.cert_status = cert_status;

                resolve(cert_info);
            }, o_certificate);

        });
    }

    // Работа над заполнением сведений о выбранном сертификате
    static FillCertInfo_Async(){

        let oCertificate = GeCades.getSelectedCertificateFromGlobalMap();

        cadesplugin.async_spawn(function*(args){

            let SubjectName;   // Владелец
            let IssuerName;    // Издатель
            let ValidFromDate; // Дата выдачи
            let ValidToDate;   // Срок действия

            try{

                SubjectName = GeCades.extractCN(yield args[0].SubjectName);
                IssuerName = GeCades.extractCN(yield args[0].IssuerName);
                ValidFromDate = new Date(yield args[0].ValidFromDate);
                ValidToDate = new Date(yield args[0].ValidToDate);
            }catch(ex){
                console.log("Ошибка при получении свойства SubjectName / IssuerName / ValidFromDate / ValidToDate: " + cadesplugin.getLastError(ex));
            }

            let Validator;
            let IsValid = undefined; // В случае неизвестного алгоритма

            // Если попадется сертификат с неизвестным алгоритмом, то
            // тут будет исключение. В таком сертификате просто такое поле
            try{
                Validator = yield args[0].IsValid();
                IsValid = yield Validator.Result;
            }catch(ex){}

            let hasPrivateKey; // Привязка сертификата к закрытому ключу

            try{
                hasPrivateKey = yield args[0].HasPrivateKey();
            }catch(ex){
                console.log("Ошибка при получении свойства HasPrivateKey: " + cadesplugin.getLastError(ex));
            }


            let Now = new Date();
            let CertMessage;
            let CertStatus = false;

            if(Now < ValidFromDate){
                CertMessage = 'Срок действия не наступил';
            }else if(Now > ValidToDate){
                CertMessage = 'Срок действия истек';
            }else if(!hasPrivateKey){
                CertMessage = 'Нет привязки к закрытому ключу';
            }else if(IsValid === false){
                CertMessage = 'Ошибка при проверке цепочки сертификатов';
            }else if(IsValid === undefined){
                CertMessage = 'Сертификат с неизвестным алгоритмом';
            }else{
                CertMessage = 'Действителен';
                CertStatus = true;
            }

            // Внесение данных о сертификате
            document.getElementById('SubjectName').innerHTML = SubjectName;
            document.getElementById('IssuerName').innerHTML = IssuerName;
            document.getElementById('ValidFromDate').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(ValidFromDate);
            document.getElementById('ValidToDate').innerHTML = GeCades.formattedDateTo_ddmmyyy_hhmmss(ValidToDate);
            document.getElementById('CertMessage').innerHTML = CertMessage;
            document.getElementById('CertMessage').style.color = CertStatus ? '#6cb37e' : '#db5151';



            console.log('fill cert info finished');


            //TODO вынести отдельно
            SignHandler.plugin_info = SignHandler.modal.querySelector('.sign-modal__cert-info');
            SignHandler.plugin_info.dataset.inactive = 'false';

        }, oCertificate);
    }



    static GetHashedData_Async(base64Data){

        return new Promise((resolve, reject) => {

            cadesplugin.async_spawn(function*(args){

                // TODO проверку на не null
                let SelectedCertificate = GeCades.getSelectedCertificateFromGlobalMap();

                let PublicKey = yield SelectedCertificate.PublicKey();

                let Algorithm = yield PublicKey.Algorithm;

                let AlgorithmValue = yield Algorithm.Value;

                // TODO проверку на не null
                let alg = GeCades.getAlgorithmByValue(AlgorithmValue);

                // Создаем объект CAdESCOM.HashedData
                let oHashedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.HashedData");

                console.log(oHashedData);

                yield oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);

                yield oHashedData.propset_Algorithm(alg);

                let str = args[0];
                let length = str.length;
                let chunkSize = 100000;

                try{

                    while(length > chunkSize){

                         yield oHashedData.Hash(str.substring(0, chunkSize));
                         str = str.substring(chunkSize, str.length);
                         length -= chunkSize;
                    }
                    yield oHashedData.Hash(str);
                }catch(ex){
                    args[2]('Ошибка при заполнеии hash данных: ' + cadesplugin.getLastError(ex));
                    return;
                }

                args[1](oHashedData);
            }, base64Data, resolve, reject);
        })
    }


    static SignHash_Async(hash_alg, s_hash_value){

        return new Promise((resolve, reject) => {

            //todo сделать проверку на нулл
            let oCertificate = GeCades.getSelectedCertificateFromGlobalMap();

            cadesplugin.async_spawn(function*(args){

                let Signature;
                try{

                    let oSigner;

                    try{

                        oSigner = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPSigner");
                    }catch(ex){
                        throw 'Ошибка при создании объекта CPSigner: ' + ex.number;
                    }


                    // Создаем объект CAdESCOM.HashedData
                    let oHashedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.HashedData");

                    //yield oHashedData.propset_DataEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);
                    let algorithm = GeCades.getAlgorithmByValue(hash_alg);
                    yield oHashedData.propset_Algorithm(algorithm);
                    yield oHashedData.SetHashValue(s_hash_value);


                    // Атрибуты усовершенствованной подписи
                    let oSigningTimeAttr = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPAttribute");

                    yield oSigningTimeAttr.propset_Name(cadesplugin.CAPICOM_AUTHENTICATED_ATTRIBUTE_SIGNING_TIME);
                    yield oSigningTimeAttr.propset_Value(new Date());
                    let attr = yield oSigner.AuthenticatedAttributes2;
                    yield attr.Add(oSigningTimeAttr);

                    let oDocumentNameAttr = yield cadesplugin.CreateObjectAsync("CAdESCOM.CPAttribute");
                    yield oDocumentNameAttr.propset_Name(cadesplugin.CADESCOM_AUTHENTICATED_ATTRIBUTE_DOCUMENT_NAME);
                    yield oDocumentNameAttr.propset_Value("Document Name");
                    yield attr.Add(oDocumentNameAttr);

                    if(oSigner){
                        yield oSigner.propset_Certificate(oCertificate);
                    }else{
                        throw 'Ошибка при добавлении атрибутов к объекту CPSigner';
                    }

                    let oSignedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");
                    yield oSignedData.propset_ContentEncoding(cadesplugin.CADESCOM_BASE64_TO_BINARY);

                    yield oSigner.propset_Options(cadesplugin.CAPICOM_CERTIFICATE_INCLUDE_WHOLE_CHAIN);

                    try{
                        Signature = yield oSignedData.SignHash(oHashedData, oSigner, cadesplugin.CADESCOM_CADES_BES);
                    }catch(ex){
                        throw 'Ошибка при создании подписи: ' + cadesplugin.getLastError(ex);
                    }

                }catch(ex){
                    reject(ex);
                    return;
                }
                resolve(Signature);
            });
        })
    }

    static VerifySignature_Async(oHashedData, Signature){

        return new Promise((resolve, reject) => {

            cadesplugin.async_spawn(function*(args){

                let oSignedData = yield cadesplugin.CreateObjectAsync("CAdESCOM.CadesSignedData");

                try{
                    yield oSignedData.VerifyHash(args[0], args[1], cadesplugin.CADESCOM_CADES_BES);
                }catch(ex){
                    args[3]('Ошибка при проверке подписи: ' + cadesplugin.getLastError(ex));
                    return;
                }


                let oSigner;

                try{

                    let oSigners = yield oSignedData.Signers;
                    oSigner = yield oSigners.Item(1);
                }catch(ex){
                    args[3]('Ошибка при получений сведений о подписанте: ' + cadesplugin.getLastError(ex));
                    return;
                }


                let oSignerCertificate = yield oSigner.Certificate;


                let SubjectName;   // Владелец
                let IssuerName;    // Издатель
                let ValidFromDate; // Дата выдачи
                let ValidToDate;   // Срок действия
                let SigningTime;   // Дата подписи

                try{

                    SubjectName = yield oSignerCertificate.SubjectName;
                    IssuerName = yield oSignerCertificate.IssuerName;
                    ValidFromDate = new Date(yield oSignerCertificate.ValidFromDate);
                    ValidToDate = new Date(yield oSignerCertificate.ValidToDate);
                    SigningTime = new Date(yield oSigner.SigningTime);
                }catch(ex){

                    args[3]("Ошибка при получении свойства SubjectName / IssuerName / ValidFromDate / ValidToDate / SigningTime: " + cadesplugin.getLastError(ex));
                    return;
                }

                SubjectName =  GeCades.extractCN(SubjectName);
                IssuerName = GeCades.extractCN(IssuerName);
                ValidFromDate = GeCades.formattedDateTo_ddmmyyy_hhmmss(ValidFromDate);
                ValidToDate = GeCades.formattedDateTo_ddmmyyy_hhmmss(ValidToDate);
                SigningTime = GeCades.formattedDateTo_ddmmyyy_hhmmss(SigningTime);

                console.log(SubjectName);
                console.log(IssuerName);
                console.log(ValidFromDate);
                console.log(ValidToDate);
                console.log(SigningTime);



                args[2](true);

            }, oHashedData, Signature, resolve, reject);
        })
    }

    static getSelectedCertificateAlgorithm(){

        return new Promise((resolve, reject) => {

            cadesplugin.async_spawn(function*(args){

                let SelectedCertificate = GeCades.getSelectedCertificateFromGlobalMap();

                let PublicKey;
                let Algorithm;
                let AlgorithmValue;

                try {

                    PublicKey = yield SelectedCertificate.PublicKey();

                    Algorithm = yield PublicKey.Algorithm;

                    AlgorithmValue = yield Algorithm.Value;

                    args[0](AlgorithmValue);

                }catch(ex){

                    args[1](ex);
                }
            }, resolve, reject);
        })

    }


    static GetCertificateInfo(oCertificate){

        return new Promise((resolve, reject) => {

            cadesplugin.async_spawn(function*(args){

                let map = new Map();

                let SubjectName;   // Владелец
                let IssuerName;    // Издатель
                let ValidFromDate; // Дата выдачи
                let ValidToDate;   // Срок действия

                try {

                    SubjectName = yield args[0].SubjectName;
                    IssuerName = yield args[0].IssuerName;
                    ValidFromDate = yield args[0].ValidFromDate;
                    ValidToDate = yield args[0].ValidToDate;
                }catch(ex){

                    console.log('Ошибка при установлении свойств');
                }


                map.set('Organization', GeCades.extractCN(SubjectName));
                map.set('FIO', GeCades.extractSNG(SubjectName));
            /*    map.set('IssuerName', );
                map.set('ValidFromDate', );
                map.set('ValidToDate', yield );*/

                console.log(map);



            }, oCertificate, resolve, reject);
        })
    }


    //
    // start блок хелпереров ЭЦП -------------------------------------------------------------------------------
    //

    // Геттер и сеттер select'а с сертификатами
    static setCertificatesList(cert_list){
        GeCades.certificatesList = cert_list;
    }
    //todo тут сделать проверки на существование этого листа
    static getCertificatesList(){
        return GeCades.certificatesList;
    }

    // Геттер и сеттер map'а с сертификатами
    static setCertificateToGlobalMap(thumbprint, cert){
        if(!('globalCertsMap' in GeCades)){
            GeCades.globalCertsMap = new Map();
        }
        GeCades.globalCertsMap.set(thumbprint, cert);
    }
    static getGlobalCertificatesMap(){
        if('globalCertsMap' in GeCades){
            return GeCades.globalCertsMap;
        }else{
            return null;
        }
    }

    static getSelectedCertificateFromGlobalMap(){
        if(!('globalCertsMap' in GeCades)){
            return null;
        }
        let select = GeCades.getCertificatesList();
        let selectedIndex = select.selectedIndex;
        let key = select.options[selectedIndex].value;
        return GeCades.globalCertsMap.get(key);
    }

    // Возвращает код алгоритма в зависимости от значения в открытом ключе
    static getAlgorithmByValue(value){
        switch(value){
            case '1.2.643.7.1.1.1.1' : return cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_256; // 101
            case '1.2.643.7.1.1.1.2' : return cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411_2012_512; // 102
            case '1.2.643.2.2.19'    : return cadesplugin.CADESCOM_HASH_ALGORITHM_CP_GOST_3411;          // 100
            default :return null;
        }
    }

    //
    // finish блок хелпереров ЭЦП ------------------------------------------------------------------------------
    //





    //
    // start блок форматирования данных для вывода -------------------------------------------------------------
    //

    // Возвращает CN (организация) сведения
    static extractCN(SubjectName){

        let indCN = SubjectName.indexOf('CN', 0);
        let sep = SubjectName.indexOf(',', indCN);

        return SubjectName.slice(indCN, sep);
    }

    // Возвращает SN (фамилия), G (имя отчество) сведения
    static extractSNG(SubjectName){

        let indSN = SubjectName.indexOf('SN', 0);
        let indG = SubjectName.indexOf('G', 0);

        let sepSN = SubjectName.indexOf(',', indSN);
        let sepG = SubjectName.indexOf(',', indG);

        return SubjectName.slice(indSN, sepSN) + ' ' + SubjectName.slice(indG + 2, sepG);
    }



    // Возвращает форматированную дату из объекта Date
    // в формате дд.мм.гггг
    static formattedDateTo_ddmmyyyy(date){

        let monthDate = GeCades.AddZero(date.getDate() + 1);
        let month = GeCades.AddZero(date.getMonth());
        return monthDate + '.' + month + '.' +date.getFullYear();
    }

    // Возвращает форматированную дату из объекта Date
    // в формате дд.мм.гггг чч:мм:сс
    static formattedDateTo_ddmmyyy_hhmmss(date){

        let first = GeCades.formattedDateTo_ddmmyyyy(date);
        let H = GeCades.AddZero(date.getHours());
        let M = GeCades.AddZero(date.getMinutes());
        let S = GeCades.AddZero(date.getSeconds());
        return first + ' ' + H + ':' + M + ':' + S
    }

    // Дополняет число нулем, если оно меньше 10
    static AddZero(int){
        return (int < 10)? '0' + int : int;
    }

    //
    // finish блок форматирования данных для вывода ------------------------------------------------------------
    //
}



