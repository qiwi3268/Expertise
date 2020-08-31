<?php


$addresses = ['vam@ge74.ru'];

$header = '
<div style="margin-top: 20px; margin-bottom: 50px; background-color: rebeccapurple; border-radius: 4px">
    <div style="padding: 20px; font-size: 20px; text-align: center; color: white;">
        <span>Замечательный проект</span>
    </div>
</div>
';

try{

    $mailAddon = new PHPMailerAddon();

    array_map([$mailAddon, 'addAddress'], $addresses);

    $mailAddon->addReplyTo('help@ge74.ru', 'Хэлп');

    $mailAddon->addAttachment(ROOT.'/uploads/goods.xml');
    $mailAddon->addAttachment(ROOT.'/uploads/goods1.xml');


    $altBody = 'Текст этого письма....Хорошего дня!';

    $body = $header.$altBody;

    if($mailAddon->sendHtml('Тема', $body, $altBody)){

        echo 'Сообщения отправлены успешно!';
    }else{

        echo 'Ошибка при отправке сообщения!';
    }

}catch(Exception $e){

    var_dump($e->getCode());
    var_dump($e->getMessage());
}
