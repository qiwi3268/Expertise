<?php


namespace Classes;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require _ROOT_.'/vendor/autoload.php';

// Класс-обертка для работы с PHPMailer
//
class PHPMailerAddon{

    private PHPMailer $mail;

    public function __construct(){

        $this->mail = new PHPMailer(true);

        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.yandex.ru';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'portal@ge74.ru';
        $this->mail->Password = 'XIiVHEI18aAT';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;

        $this->mail->setFrom('portal@ge74.ru', 'Замечательный проект');
    }


    // Предназначен для добавления получателя к письму
    // Принимает параметры-----------------------------------
    // address string : email адрес получателя
    // name    string : имя получателя (необязательно)
    //
    public function addAddress(string $address, string $name = ''):void {
        $this->mail->addAddress($address, $name);
    }


    // Предназначен для добавления прикрепленных файлов к письму
    // Принимает параметры-----------------------------------
    // path     string : путь к файлу
    // name     string : имя файл, с которым он будет отправлен (!включая расширение)
    // encoding string : тип кодировки вложения
    // type     string : MIME type вложения
    //
    public function addAttachment(string $path, string $name = '', string $encoding = PHPMailer::ENCODING_BASE64, string $type = ''):void {
        $this->mail->addAttachment($path, $name, $encoding, $type);
    }


    // Предназначен для добавления адреса для ответа на отправленное нами письмо
    // Принимает параметры-----------------------------------
    // address string : email адрес для ответа
    // name    string : имя получателя ответа (необязательно)
    //
    public function addReplyTo(string $address, string $name = ''):void {
        $this->mail->addReplyTo($address, $name);
    }


    // Предназначен для отправки простого письма
    // Принимает параметры-----------------------------------
    // subject string : тема письма
    // body    string : текст письма
    // Возвращает параметры----------------------------------
    // true  : письмо отправлено
    // false : письмо неотправлено
    //
    public function sendSimple(string $subject, string $body):bool {
        $this->mail->isHTML(false);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        return $this->send();
    }


    // Предназначен для отправки HTML письма
    // Принимает параметры-----------------------------------
    // subject string : тема письма
    // body    string : текст письма
    // altBody string : текст письма для почтовых клиентов, не использующих HTML
    // Возвращает параметры----------------------------------
    // true  : письмо отправлено
    // false : письмо неотправлено
    //
    public function sendHTML(string $subject, string $body, string $altBody):bool {
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = $altBody;

        return $this->send();
    }


    // Предназначен для оправки письма и возврата результата отправки
    // Возвращает параметры----------------------------------
    // true  : письмо отправлено
    // false : письмо неотправлено
    //
    private function send():bool {
        return $this->mail->send() !== false ? true : false;
    }
}

