<?php


namespace Lib\Singles;

use PHPMailer\PHPMailer\Exception as PHPMailerEx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


require ROOT . '/vendor/autoload.php';


/**
 * Класс-обертка для работы с PHPMailer
 *
 * Паттерн: <i>Facade</i>
 *
 */
class PHPMailerFacade
{

    private PHPMailer $mail;


    /**
     * Конструктор класса
     *
     * @throws PHPMailerEx
     */
    public function __construct()
    {
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


    /**
     * Предназначен для добавления получателя к письму
     *
     * @param string $address email адрес получателя
     * @param string $name имя получателя <i>(необязательно)</i>
     * @throws PHPMailerEx
     */
    public function addAddress(string $address, string $name = ''): void
    {
        $this->mail->addAddress($address, $name);
    }


    /**
     * Предназначен для добавления вложения (файла) к письму
     *
     * @param string $path путь к файлу
     * @param string $name имя файл, с которым он будет отправлен <b>(включая расширение)</b>
     * @param string $encoding тип кодировки вложения
     * @param string $type MIME type вложения
     * @throws PHPMailerEx
     */
    public function addAttachment(string $path, string $name = '', string $encoding = PHPMailer::ENCODING_BASE64, string $type = ''): void
    {
        $this->mail->addAttachment($path, $name, $encoding, $type);
    }


    /**
     * Предназначен для добавления адреса для ответа на отправленное нами письмо
     *
     * @param string $address email адрес для ответа
     * @param string $name имя получателя ответа <i>(необязательно)</i>
     * @throws PHPMailerEx
     */
    public function addReplyTo(string $address, string $name = ''): void
    {
        $this->mail->addReplyTo($address, $name);
    }


    /**
     * Предназначен для отправки простого письма
     *
     * @param string $subject тема письма
     * @param string $body текст письма
     * @return bool <b>true</b> письмо отправлено<br><b>false</b> письмо неотправлено
     * @throws PHPMailerEx
     */
    public function sendSimple(string $subject, string $body): bool
    {
        $this->mail->isHTML(false);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        return $this->send();
    }


    /**
     * Предназначен для отправки HTML письма
     *
     * @param string $subject тема письма
     * @param string $body текст письма
     * @param string $altBody текст письма для почтовых клиентов, не использующих HTML
     * @return bool <b>true</b> письмо отправлено<br>
     * <b>false</b> письмо неотправлено
     * @throws PHPMailerEx
     */
    public function sendHTML(string $subject, string $body, string $altBody): bool
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = $altBody;

        return $this->send();
    }


    /**
     * Предназначен для оправки письма и возврата результата отправки
     *
     * @return bool <b>true</b> письмо отправлено<br>
     * <b>false</b> письмо неотправлено
     * @throws PHPMailerEx
     */
    private function send(): bool
    {
        return $this->mail->send() !== false;
    }
}

