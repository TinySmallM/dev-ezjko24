<b style="font-size: 16px">Здравствуйте!</b><br><br>

В магазине появился новый заказ.<br><br>

<b>Информация о клиенте:</b><br>
ФИО: <?=$o['name']?><br>
Телефон: <?=$o['phone']?><br>
Email: <?=$o['email']?><br>

<?if($o['comment']){?>
Комментарий:<br> <?=$o['comment']?><br><br>
<?}?>


<a href="https://<?=Yii::$app->params['domain']?>/master/order/<?=$o['id']?>">Нажмите сюда</a>, чтобы перейти к управлению
