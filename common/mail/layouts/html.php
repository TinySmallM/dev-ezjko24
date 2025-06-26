<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&amp;amp;family=Raleway:wght@700&amp;amp;display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        font-weight: 400;
        color: #333;
        overflow-x: hidden;
        font-size: 13px; 
    }
    body p{
        font-size: 1rem;
    }
    body .header{
        font-size: 1.3rem;
        font-weight: 600;
    }
    body .small{
        font-size: 0.7rem;
    }
    body .colorgray{
        color: #333;
    }
  </style>    
</head>
<body>
    <?php $this->beginBody() ?>

    <img src="https://rus-electronika.ru/img/re-logo-_1_.png" style="display:block;margin: 0 auto;width: 250px;margin-bottom: 50px;">

    <div style="margin: 0 auto;display: block;max-width: 700px;width: 100%;">
        <p>
            <?= $content ?>
        </p>
    </div>

    <br>
    <br>

    <hr>
    <p>
        С уважением, команда <a style="color:#333" href="https://rus-electronika.ru">rus-electronika.ru</a>
    </p>
    <p class="small">Письмо отправлено <?=$this->params['email_to']?> <?=date('d.m.Y H:i:s')?> потому что вы зарегистрированы в нашем магазине.</p>
    <?/*
    <a class="small colorgray" href="https://rus-electronika.ru/lk/unsubscribe?email=<?=$this->params['email_to']?>&code=<?=md5($this->params['email_to'].'m4kofm3fklsdmfmsff#')?>">Отписаться от рассылки</a>
    */?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
