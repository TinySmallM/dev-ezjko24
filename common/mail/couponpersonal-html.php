<b style="font-size: 22px">Привет!</b><br>

<p>

    У нас есть подарок за твою недавнюю покупку 😊<br>

    Отправляем персональный промокод на скидку <b><?=round($c['amount'])?> <?=$c['type']=='percent'?"%":"РУБ"?>.</b><br>

    <br>Обрати внимание! Он будет действовать <b>7 ДНЕЙ</b> на все товары, включая боксы, только для твоего Email.<br><br>

    Выбери то, что давно хочешь приобрести ❤️<br><br>
</p>

<p>
Твой промокод: <span style="border: 2px solid #9d9387;padding: 2px 6px 4px 6px;font-size: 24px;color: #9d9387;font-weight: 600;"><?if($c['text']){?><?=$c['text']?><?}?><span>
</p>

<br>

<a style="font-size: 20px;color: #9d9387;font-weight: 600;" href="https://christmedschool.com?utm_source=email&utm_medium=promo&utm_campaign=after_buy&utm_content
=<?=$c['text']?>">> Выбрать курс</a>