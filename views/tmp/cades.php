<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <script language="javascript" src="/views/js/lib/cadesplugin_api.js"></script>
    <script language="javascript" src="/views/js/tmp/cades.js"></script>
    <script language="javascript" src="/views/js/tmp/PerfectCades.js"></script>
</head>
<body>

<hr/>
Версия плагина: <span id="PlugInVersionTxt" lang="ru"></span>
<br/>
Версия криптопровайдера: <span id="CSPVersionTxt" lang="ru"></span>
<hr/>

<select size="10" name="CertListBox" id="CertListBox" style="width: 100%;"></select>


<hr/>
<span>Данные о выбранном сертификате:</span>
<br/>

Владелец: <span id="SubjectName"></span>
<br/>
Издатель: <span id="IssuerName"></span>
<br/>
Дата выдачи: <span id="ValidFromDate"></span>
<br/>
Срок действия: <span id="ValidToDate"></span>
<br/>
Статус: <span id="CertStatus"></span>
<hr/>


<input id="signature_file" name="myFile" type="file">
<br/>
<input id="signature_button" value="Подписать" type="button">

<br/>
<br/>
<br/>

<input id="internal_signature_file" type="file">
<br/>
<input id="internal_signature_button" value="Проверить встроенную подпись" type="button">


</body>
</html>