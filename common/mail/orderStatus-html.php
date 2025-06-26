<?
    //$isSept = null;
    $isSept2 = null;
    
    foreach($o['items'] as $d){
        //if($d['product_id'] == 189) $isSept = 1;
        if($d['product_id'] == 187) $isSept2 = 1;
    }
    

?>

<? if($isSept2){ ?>

<b style="font-size: 16px">Привет, абитуриент!</b>
<br>
<p>Впереди тебя ждёт тернистый путь обучения в медицинском вузе, но мы поможет сделать его интересным и веселым!</p>
<p>Мы очень счастливы, что начать обучение ты решил вместе с нами!</p>
<p>Мы гарантируем высококачественную информацию и лучшие материалы для подготовки к обучению!</p>
<p>Присоединяйся в канал и чат &ndash; там тебя уже ждёт наша команда преподавателей, которые с помогут на пути к успешному обучению в первый год!</p>
<br>
<p>Ссылка на канал: <a href="https://t.me/+SCjVncrZgshkOGIy">https://t.me/+SCjVncrZgshkOGIy</a></p>
<p>Ссылка на чат: <a href="https://t.me/+Cg0omb1edJYxMzEy">https://t.me/+Cg0omb1edJYxMzEy</a></p>
<br>
<p>Желаем успехов!</p>

<?} else {?>

<b style="font-size: 16px">Здравствуйте!</b>
<br>
<br>
Ваш заказ успешно оплачен.<br><br>

<b>Как получить доступ к материалам:</b><br>
1. Войдите в ваш личный кабинет по ссылке: <a style="color:#333;font-weight: 600;" href="https://christmedschool.com/lk">christmedschool.com/lk</a><br><br>
[ ! ] Учетная запись создается <b>автоматически</b> после оплаты заказа.<br>
[ ! ] В качестве логина используйте почту, на которую вы получили это письмо.<br><br>

<?/*
1. Зарегистрируйтесь/войдите в свой аккаунт по ссылке: <a style="color:#333" href="https://school.christmedschool.com">school.christmedschool.com</a><br><br>

2. Введите код активации в разделе меню «Активация». 

<br>
Ваш код активации: <?if($o['platformCode']){?><b style="font-size: 14px; color: "><?=$o['platformCode']?></b><?}?>

<br><br>
*/?>

2. Перейдите в раздел "Мой курс"<br><br>

3. Выбериет интересующий вас предмет<br><br>

4. Выберите слева тему (у купленных тем не будет иконки замочка)
<br><br>

Если возникнут какие-либо вопросы, мы всегда на связи:<br>
<a href="mailto:support@christmedschool.com">support@christmedschool.com</a>
<?}?>