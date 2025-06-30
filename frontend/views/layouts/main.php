<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;
use common\models\Helpers;
use common\models\Page;

AppAsset::register($this);

$items_pages = Page::find()->with(['child'=>function($q){
  $q->where(['published'=>1]);
}])->where(['published'=>1,'template'=>2,'parent'=>0])->asArray()->all();

$this->registerCss('


');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta name="mailru-domain" content="78Dzuwc3qrGjHqCV" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<meta name="yandex-verification" content="628bdc1efbdac309" />
    <meta name="og:title" content="<?=$this->title?>" />
    <meta name="og:url" content="<?=Url::base(true)?>" />
    <meta name="og:type" content="website">
    <?=$this->render('_head');?>
    <title><?= Html::encode( $this->title ) ?></title>
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>

	<script>
		var csrfParam = '<?=Yii::$app->request->csrfParam?>';
		var csrfToken = '<?=Yii::$app->request->getCsrfToken()?>'

	</script>

    <script type="application/ld+json">
    {
        "@context":"https://schema.org/",
        "@type":"Product",
        "name":"<?= Html::encode( $this->title ) ?>",
        "image":"",
        "aggregateRating":{
            "@type":"AggregateRating",
            "ratingValue":"5",
            "bestRating":"5",
            "worstRating":"4.1",
            "ratingCount":"133"
        }
    }
    </script> 


</head>
<body>
	
<?php $this->beginBody() ?>

<main>
    <?= $content ?>
</main>

<nav id="mobile-nav">
    <ul>
        <li>
            <a href="#submenu-main" class="openSubmenuHref">Каталог товаров</a>
            <ul id="#submenu-main">
              <?foreach($items_pages as $i=>$d){?>
                <?if(!$d['child']){?>
                  <li><a href="<?=$d['url']?>"><?=$d['h1']?></a></li>
                <?}else{?>
                    <li>
                      <a href="#submenu-<?=$d['id']?>" class="openSubmenuHref"><?=$d['h1']?></a>
                      <ul id="#submenu-<?=$d['id']?>">
                        <?foreach($d['child'] as $i_i=>$d_d){?>
                          <li><a href="<?=$d_d['url']?>"><?=$d_d['h1']?></a></li>
                        <?}?>
                      </ul>
                    </li>
                <?}?>
              <?}?>
            </ul>
        </li>
      <li><a href="/otzyvy">Отзывы</a></li>
      <li><a href="/about">О нас</a></li>
      <li><a href="/kontakty">Контакты</a></li>
    </ul>
</nav>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("#my-menu a[href^='#']").forEach(link => {
        link.addEventListener("click", function(event) {
            // Проверяем, есть ли у ссылки подменю
            const submenu = document.querySelector(this.getAttribute("href"));
            if (submenu) {
                event.preventDefault(); // Предотвращаем переход по ссылке
                menu.openPanel(submenu); // Открываем подменю
            }
        });
    });
})
</script>

<!-- (mobile-nav) -->
<?/*
<div class="mobile-nav" id="mobile-nav">
  <div class="mobile-nav__wrapper" id="mobile-nav-wrapper">
    <div class="mobile-nav__section-container">
      <div class="mobile-nav__section mobile-nav-section visible">
        <div class="mobile-nav__sub-section mobile-nav-container visible" id="mobile-nav-1-sub-mobile-nav-1">
          <div class="mobile-nav__info">
            <div class="mobile-nav__info-cover cover">
              <div class="mobile-nav__info-wrapper">
                <div class="h3 mobile-nav__info-title">Меню</div>
                <button class="mobile-nav__info-btn mobile-nav__info-btn_cross" data-fancybox-close>
                  <svg class="mobile-nav__info-btn-icon mobile-nav__info-btn-icon_cross">
                    <use xlink:href="/img/icons.svg#icon-cross"></use>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <ul class="mobile-nav__list">
            
          <li class="mobile-nav__item">
              <a href="#" class="mobile-nav__item-link mobile-nav-link" data-src="#menu-pages">
                <div class="mobile-nav__item-cover cover">
                  <div class="mobile-nav__item-wrapper">
                    <div class="mobile-nav__item-name">Каталог товаров</div>
                    <svg class="mobile-nav__item-arrow-icon">
                      <use xlink:href="/img/icons.svg#icon-arrow-mutable"></use>
                    </svg>
                  </div>
                </div>
              </a>
            </li>


            <li class="mobile-nav__item">
              <a href="/kontakty" class="mobile-nav__item-link mobile-nav-link">
                <div class="mobile-nav__item-cover cover">
                  <div class="mobile-nav__item-wrapper">
                    <div class="mobile-nav__item-name">О магазине</div>
                  </div>
                </div>
              </a>
            </li>

            <li class="mobile-nav__item">
              <a href="/kontakty" class="mobile-nav__item-link mobile-nav-link">
                <div class="mobile-nav__item-cover cover">
                  <div class="mobile-nav__item-wrapper">
                    <div class="mobile-nav__item-name">Контакты</div>
                  </div>
                </div>
              </a>
            </li>

            <li class="mobile-nav__item">
              <a href="/warreny" class="mobile-nav__item-link mobile-nav-link">
                <div class="mobile-nav__item-cover cover">
                  <div class="mobile-nav__item-wrapper">
                    <div class="mobile-nav__item-name">Гарантии</div>
                  </div>
                </div>
              </a>
            </li>

            <li class="mobile-nav__item">
              <a href="/delivery" class="mobile-nav__item-link mobile-nav-link">
                <div class="mobile-nav__item-cover cover">
                  <div class="mobile-nav__item-wrapper">
                    <div class="mobile-nav__item-name">Доставка и оплата</div>
                  </div>
                </div>
              </a>
            </li>

          </ul>
        </div>
      </div>

    </div>
  </div>
</div>
*/?>
<!-- End (mobile-nav) -->





<?php $this->endBody() ?>

<?=$this->render('../_blocks/cart_widget')?>

<script src="//code.jivo.ru/widget/M5Gew1resZ" async></script>


</body>
</html>
<?php $this->endPage() ?>