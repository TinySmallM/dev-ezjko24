<?php



use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1'])]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
if($data['image']) $this->registerMetaTag(['property' => 'og:image', 'content' => '/upload/'.$data['image']]);

$this->title = Helpers::phReplace($data['title'].' – купить');

$this->registerCss("


");

?>

<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
    <?=$this->render('../_blocks/nt_header')?> 
 
 
    <!-- breadcrumbs-->
    <section class="breadcrumbs">
        <div class="cover breadcrumbs__cover">
        <div class="breadcrumbs__wrapper">

            <a href="/">rus-electronika.ru</a>
            <svg class="breadcrumbs__arrow">
            <use xlink:href="/img/icons.svg#sharp-arrow"></use>
            </svg>

            <a href="/<?=$data['url']?>"><?=$data['h1']?></a>
            <svg class="breadcrumbs__arrow">
            <use xlink:href="/img/icons.svg#sharp-arrow"></use>
            </svg>

        </div>
        </div>
    </section>

    <!--  -->
    <section class="detail ">
    <div class="cover detail__cover">
        <h1 class="h1 detail__title"><?=$data['h1']?></h1>
        <div class="detail__code">Код товара: <?=$data['id']?></div>
        <div class="detail__wrapper">
        <div class="detail__main-info">
            <div class="detail__gallery-cover">
            <div class="detail__gallery-container fotorama " data-width="100%" data-ratio="1000/600" data-nav="thumbs" data-allowfullscreen="true" data-thumbwidth="30" data-thumbheight="30">

                            
                <?foreach($data['gallery'] as $key=>$d){?>
                    <img 
                        itemprop="image"
                        data-thumb="/upload/thumb_<?=$d['image']?>"  
                        src="/upload/<?=$d['image']?>"  
                        alt="<?=Helpers::phReplace($data['title'])?>" 
                        title="<?=Helpers::phReplace($data['title'])?>"
                    >
                <?}?>

            </div>
            </div>
            <div class="detail__info">
            <div class="detail__info-content">
                <div class="detail__availability">
                    
                    <?if($data['price1_sum']){?>
                        <div class="detail__price-container">
                            <div class="detail__price"><?=number_format($data['price1_sum'], 0, ',', ' ')?> <span>₽</span>
                            </div>
                        </div>
                    <?}?>

                    <div class="detail__status detail__status_success">
                        <svg class="detail__status-icon">
                        <use xlink:href="/img/icons.svg#check-icon"></use>
                        </svg>
                        В наличии
                    </div>

                </div>
                <div class="detail__settings">

                <!--
                <div class="detail__setting-section">
                    <div class="detail__color-container">
                    <h6 class="h4 detail__settings-title detail__color-title">Цвет:</h6>
                    <div class="detail__color-content">
                        <div class="detail__color-list">

                        <div class="detail__color-item">
                            <label class="detail__color-btn">
                            <input type="radio" name="color" value="/catalogue/iphone/iphone_15/284470.html"
                                class="detail__color-input item_color_choose" checked>
                            <span class="detail__color-img-container" title="Зелёный">


                                <img src="/img/icon-color-green.png" alt="Зелёный" class="detail__color-img">

                            </span>
                            </label>
                        </div>

                        </div>
                    </div>
                    </div>
                </div>
                    -->

                <div class="detail__setting-section">
                    <div class="detail__variables-container">
                    <div class="detail__variables-content">
                        <strong>Гарантия:</strong> магазина 12 месяцев
                    </div>
                    </div>
                </div>

                </div>
                <div class="detail__footnote">
                <div class="detail__social-wrapper">
                    <div class="detail__social-label">Поделиться:</div>
                    <div class="detail__social-content">
                    <ul class="detail__social-list">
                        <li class="detail__social-item">
                        <a href="#" class="detail__social-link" target="_blank">
                            <svg class="detail__social-icon">
                            <use xlink:href="/assets/sites/toys/images/icons.svg#instagram-icon"></use>
                            </svg>
                        </a>
                        </li>
                        <li class="detail__social-item">
                        <a href="#" class="detail__social-link" target="_blank">
                            <svg class="detail__social-icon">
                            <use xlink:href="/img/icons.svg#vk-icon"></use>
                            </svg>
                        </a>
                        </li>
                        <li class="detail__social-item">
                        <a href="#" class="detail__social-link" target="_blank">
                            <svg class="detail__social-icon">
                            <use xlink:href="/img/icons.svg#facebook-icon"></use>
                            </svg>
                        </a>
                        </li>
                        <li class="detail__social-item">
                        <a href="#" class="detail__social-link" target="_blank">
                            <svg class="detail__social-icon">
                            <use xlink:href="/img/icons.svg#telegram-icon"></use>
                            </svg>
                        </a>
                        </li>
                        <li class="detail__social-item">
                        <script src="https://yastatic.net/share2/share.js"></script>
                        <div class="ya-share2" data-curtain data-shape="round" data-color-scheme="whiteblack"
                            data-limit="0" data-more-button-type="short"
                            data-services="vkontakte,odnoklassniki,telegram,whatsapp"></div>
                        </li>
                    </ul>
                    </div>
                </div>
                <?if($data['price1_sum']){?>
                <div class="detail__btn-container">
                    <!-- (btn) -->

                    <button type="button" class="btn detail__btn addCart" data-buypricetype="1" data-buyid="<?=$data['id']?>" data-price="<?=$data['price1_sum']?>">Купить</button>

                    <!-- End (btn) -->
                </div>
                <?}?>
                </div>
            </div>
            </div>
        </div>
        <div class="detail__sub-info">
            <div class="detail__advantages-list">
            <div class="detail__advantage">
                <div class="detail__advantage-head">
                <div class="detail__advantage-icon-container">
                    <img src="/img/advantage-icon-1.png" alt="Проверка устройства без оплаты"
                    class="detail__advantage-icon">
                </div>
                <h6 class="h4 detail__advantage-title">Проверка устройства без оплаты</h6>
                </div>
                <div class="detail__advantage-text">В нашем офисе вы можете открыть товар до оплаты и проверить его
                сами.</div>
            </div>
            <div class="detail__advantage">
                <div class="detail__advantage-head">
                <div class="detail__advantage-icon-container">
                    <img src="/img/advantage-icon-2.png" alt="Доставка" class="detail__advantage-icon">
                </div>
                <h6 class="h4 detail__advantage-title">Доставка</h6>
                </div>
                <div class="detail__advantage-text">В день заказа, срочная или в любой регион России курьерской
                службой.</div>
            </div>
            <div class="detail__advantage">
                <div class="detail__advantage-head">
                <div class="detail__advantage-icon-container">
                    <img src="/img/advantage-icon-3.png" alt="Самовывоз" class="detail__advantage-icon">
                </div>
                <h6 class="h4 detail__advantage-title">Самовывоз</h6>
                </div>
                <div class="detail__advantage-text">Бесплатно, быстро и без очереди в офисе: Москва, ул. Промышленный
                проезд д.7 стр.4</div>
            </div>
            <div class="detail__advantage">
                <div class="detail__advantage-head">
                <div class="detail__advantage-icon-container">
                    <img src="/img/advantage-icon-1.png" alt="Заводская упаковка устройства"
                    class="detail__advantage-icon">
                </div>
                <h6 class="h4 detail__advantage-title">Заводская упаковка устройства</h6>
                </div>
                <div class="detail__advantage-text">Все товары, продающиеся у нас, находятся в заводской упаковке.
                </div>
            </div>
            <div class="detail__advantage">
                <div class="detail__advantage-head">
                <div class="detail__advantage-icon-container">
                    <img src="/img/advantage-icon-4.png" alt="Оплата" class="detail__advantage-icon">
                </div>
                <h6 class="h4 detail__advantage-title">Оплата</h6>
                </div>
                <div class="detail__advantage-text">Оплата наличными для физических лиц и безналичный рассчет для
                юридических лиц</div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
    

    <section class="full-info">
        <div class="cover full-info__cover">
        

        <?if($data['charactsfields']){?>
          <div class="full-info__section active">
            <div class="full-info__head">
              <div class="full-info__head-name">
                Технические характеристики
                <div class="full-info__head-icon">
                  <svg class="full-info__head-arrow">
                    <use xlink:href="/img/icons.svg#blunt-arrow-horizontal"></use>
                  </svg>
                </div>
              </div>
            </div>
            <div class="full-info__content">
              <div class="full-info__data">
                <div class="full-info__data-wrapper">

                    <div class="full-info__data-column">

                        <?foreach($data['charactsfields_groups'] as $gr_name){?>

                            
                            <div class="full-info__data-section">
                                <h5 class="h4 full-info__data-title"><?=$gr_name?$gr_name:'Общие'?>:</h5>
                                <ul class="full-info__data-list">





                                    <?foreach($data['charactsfields'] as $field){
                                        if($gr_name !=$field['groupName']) continue;
                                        ?>

                                        <?if($field['value']!=='Не указано'){?>
                                          <li class="full-info__data-item">
                                            
                                              <div class="full-info__data-name"><?=$field['nameRu']?></div>
                                              <div class="full-info__data-value"><?=$field['value']?></div>
                                              
                                          </li>
                                        <?}?>
                                    <?}?>

                                </ul>
                            </div>
                        
                        <?}?>

                    </div>



                </div>
              </div>
            </div>
          </div>
        <?}?>
        <?if($data['content']){?>
          <div class="full-info__section active">
            <div class="full-info__head">
              <div class="full-info__head-name">
                Описание
                <div class="full-info__head-icon">
                  <svg class="full-info__head-arrow">
                    <use xlink:href="/img/icons.svg#blunt-arrow-horizontal"></use>
                  </svg>
                </div>
              </div>
            </div>
            <div class="full-info__content">
              <div class="full-info__text">
                <?=$data['content']?>
              </div>
            </div>
          </div>
        <?}?>
          <div class="full-info__section active">
            <div class="full-info__head">
              <div class="full-info__head-name">
                Доставка и оплата
                <div class="full-info__head-icon">
                  <svg class="full-info__head-arrow">
                    <use xlink:href="/img/icons.svg#blunt-arrow-horizontal"></use>
                  </svg>
                </div>
              </div>
            </div>
            <div class="full-info__content">
              <div class="full-info__text">
                <p>Варианты оплаты</p>
                <p>Наличными курьеру или в офисе компании от частного лица или от организации.</p>
                <p>Доставка курьером до Вашей двери в пределах МКАД в течение текущего рабочего дня* - от 350 руб.</p>
                <p>Доставка малогабаритного товара (телефон, планшет) в пределах МКАД - 350 руб</p>
                <p>Доставка среднегабаритного товара (пылесос, ноутбук) в пределах МКАД - 390 руб</p>
                <p>Доставка крупногабаритного товара (телевизор) в пределах МКАД - 600 руб</p>
                <p>Стоимость доставки за пределами МКАД - от 400 до 1500 рублей.</p>
                <p>Доставка товара осуществляется только в квартиры и офисные помещения.</p>
                <p>Доставка товара в общественные места (улица, метро, вокзалы и т.д.) не производится.</p>
                <p>Доставка в регионы по 100% предоплате</p>
                <p>Условия доставки в нерабочее время, в праздничные и выходные дни уточняйте у менеджеров по телефонам:
                </p>
                <p>*Внимание: доставка товара в течение рабочего дня осуществляется если вы сделали заказ до 12:00.</p>
                <p>Также, Вы можете забрать заказ САМОВЫВОЗОМ по предварительному резерву.</p>
                <p>При оформлении заказа на организацию — просьба указывать реквизиты и желаемый способ оплаты в
                  комментариях.</p>
                <p>Безналичный перевод от организации: +10% к стоимости.</p>
                <p>Во Всех случаях, предоставляем Все необходимые документы (кассовый чек, товарная накладная), в
                  соответствии с действующим законодательством Российской Федерации.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- End (full-info) -->

    <?=$this->render('../_blocks/nt_footer')?>
  </div>
</div>


<?
$script = <<< JS

var headInfo = $('.full-info__head');
  headInfo.on('click', function () {
    var container = $(this).parent();
    var content = container.find('.full-info__content');

    if( container.hasClass('active') ){
      container.removeClass('active')
    }
    else {
      $('.full-info__section').removeClass('active');
      container.addClass('active');
      content.show();
    }

    

  });

JS;
$this->registerJs($script, yii\web\View::POS_READY);  

?>