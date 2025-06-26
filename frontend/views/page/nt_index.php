<?

use common\models\Page;

use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1'])]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
if($data['image']) $this->registerMetaTag(['property' => 'og:image', 'content' => '/upload/'.$data['image']]);

$this->title = Helpers::phReplace($data['title'].' – купить');

$items_categories = Page::find()
    ->where(['template'=>2,'published'=>1])
    //->andWhere(['!=', 'image', 'NULL'])
    ->with(['chunk'])
    ->with(['product','child'=>function($b){
      $b->with(['product']);
    }])
    ->asArray()->all();

foreach($items_categories as $i=>$d){
  if(!$d['image'] || $d['image'] == 'NULL'){

    $items_categories[$i]['image'] = null;

    foreach($d['product'] as $k=>$b){
      if($items_categories[$i]['image']) continue;
      if($b['image'] && $b['image'] != 'NULL') $items_categories[$i]['image'] = $b['image'];
    }
    
    foreach($d['child'] as $k_p=>$b_p){
      if($items_categories[$i]['image']) continue;

      if($b_p['image'] && $b_p['image'] != 'NULL') $items_categories[$i]['image'] = $b_p['image'];
      
      foreach($b_p['product'] as $k=>$b){
        if($items_categories[$i]['image']) continue;
        if($b['image'] && $b['image'] != 'NULL') $items_categories[$i]['image'] = $b['image'];
      }

    }



  }
}

?>

<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
  <?=$this->render('../_blocks/nt_header')?>

    
    <!-- (slider) -->
    <section class="slider">
      <div class="cover slider__cover">
        <div class="slider__container" id="slider-nav">
          <div class="slider__wrapper">
            <div class="slider__list" id="slider">
              <div class="slider__slide slider__slide_two">
                <div class="slider__slide-bg"></div>
                <div class="slider__content">
                  <h3 class="h3 slider__title">MacBook Air 15</h3>
                  <a href="/" class="slider__btn">Купить</a>
                  <div class="slider__sub-info">Закажи сейчас и мы доставим моментально!*</div>
                </div>
                <img src="/img/macbook_air_15_2.png" alt="Дешевле нет нигде!"
                  class="slider__img">
                <div class="slider__signature"></div>
                <h3 class="h3 slider__sub-title mob-none">Самый быстрый ноутбук<span class="slider__hidden-element-1"></span>
                </h3>
                <div class="slider__warn">*Привезем через час!</div>
              </div>
              <div class="slider__slide slider__slide_three">
                <div class="slider__slide-bg"></div>
                <div class="slider__content">
                  <h3 class="h3 slider__title">iPhone 15 Pro Max</h3>
                  <a href="/smartfony-apple-iphone" class="slider__btn">Купить</a>
                  <div class="slider__sub-info">Закажи сейчас и мы доставим моментально!*</div>
                </div>
                <img src="/img/iphone_15_pro_max.png" alt="Дешевле нет нигде!"
                  class="slider__img">
                <div class="slider__signature"></div>
                <h3 class="h3 slider__sub-title">Все, чего вы ждали!<span class="slider__hidden-element-1"></span>
                </h3>
                <div class="slider__warn">*Привезем через час!</div>
              </div>
              <div class="slider__slide slider__slide_one">
                <div class="slider__slide-bg"
                  style="  background: #2f515d url(/img/bg_slide_1.jpg) left center/cover no-repeat;">
                </div>
                <div class="slider__slide-bg"></div>
                <div class="slider__content">
                  <h3 class="h3 slider__title">Дешевле нет нигде!</h3>
                  <a href="/" class="slider__btn">Перейти в каталог</a>
                  <div class="slider__sub-info">Закажи сейчас и мы доставим моментально!*</div>
                </div>
                <img src="/img/img_slide_1.png" alt="Дешевле нет нигде!" class="slider__img">
                <div class="slider__signature">100% Оригинал</div>
                <h3 class="h3 slider__sub-title">Проверьте сами до оплаты!<span
                    class="slider__hidden-element-1"></span>
                </h3>
                <div class="slider__warn">*Привезем через час!</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="info">
      <div class="cover info__cover">
        <div class="info__wrapper">
          <div class="info__content">
            <h3 class="h3 info__title">Добро пожаловать в интернет-магазин оригинальной электроники!</h3>
            <p class="info__text">Мы предлагаем широкий ассортимент сертифицированной электроники от ведущих мировых
              производителей: Apple, Google, Xiaomi, Samsung и других. Разумный уровень цен и качественный сервис
              сделают вашу покупку быстрой и выгодной.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- (main-catalog) -->
    <section class="main-catalog">
      <div class="cover main-catalog__cover">
        <h3 class="h1 main-catalog__title">Каталог</h3>
        <div class="main-catalog__content">
          <div class="main-catalog__section">
            <h4 class="h2 main-catalog__sub-title">
              <a href="" class="main-catalog__sub-title-link">Наши товары</a>
            </h4>
            <div class="main-catalog__section-content">
              <div class="main-catalog__list">
              <?foreach($items_categories as $i=>$d){?>
                <div class="main-catalog__category">
                  <a href="/<?=$d['url']?>" class="main-catalog__category-link">
                    <h6 class="h4 main-catalog__category-name"><?=$d['h1']?></h6>
                    <div class="main-catalog__category-img-container">
                      <img src="/upload/thumb_<?=$d['image'] != 'NULL'?$d['image']:$d['product'][0]['image']?>" alt="<?=$d['h1']?>"
                        class="main-catalog__category-img">
                    </div>
                  </a>
                </div>
              <?}?>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- End (main-catalog) -->
    <!-- (sub-offers) -->
    <?/*
    <section class="sub-offers" id="tabs">
      <div class="cover sub-offers__cover">
        <ul class="sub-offers__nav">
          <li class="sub-offers__nav-item">
            <a href="#tab-new" class="sub-offers__nav-link">Новинки</a>
          </li>
          <li class="sub-offers__nav-item">
            <a href="#tab-stock" class="sub-offers__nav-link">Акции</a>
          </li>
          <li class="sub-offers__nav-item">
            <a href="#tab-popular" class="sub-offers__nav-link">Популярное</a>
          </li>
        </ul>
        <div class="sub-offers__content">
          <div class="sub-offers__info" id="tab-new">
            <!-- (carousel) -->
            <div class="carousel tab-carousel">
              <div class="carousel__wrapper">
                <div class="carousel__list tab-carousel-list">
                  <div class="carousel__item">
                    <!-- (card) -->
                    <div class="card">
                      <div class="card__img-conatiner">
                        <div class="card__sub-buttons">
                          <!-- <label class="card__compare tooltip-compare">
                                      <input type="checkbox" name="compare" value="" class="card__compare-input" data-btn="compare">
                                      <svg class="card__compare-icon">
                                      <use xlink:href="/img//icons.svg#compare-icon"></use>
                                      </svg>
                                  </label> -->
                          <label class="card__favorites tooltip-favorites">
                            <input type="checkbox" name="favorites" value="" class="card__favorites-input"
                              data-btn="favorites">
                            <svg class="card__favorites-icon">
                              <use xlink:href="/img/icons.svg#heart-icon"></use>
                            </svg>
                          </label>
                        </div>
                        <a href="/catalogue/ipad/ipad_mini__2021/206778.html" class="card__img-link">
                          <div class="card__img-wrapper">
                            <img src="/img/apple_ipad_mini__2021__wi_fi_purple_1.jpg"
                              alt="Планшет Apple iPad mini (2021) 64Gb Wi-Fi Purple" class="card__img">
                          </div>
                        </a>
                      </div>
                      <h6 class="h6 card__name">
                        <a href="/catalogue/ipad/ipad_mini__2021/206778.html" class="card__name-link">Планшет Apple
                          iPad mini (2021) 64Gb Wi-Fi Purple</a>
                      </h6>
                      <div class="card__footnote">
                        <div class="card__prices">
                          <div class="card__price-current">42890 <span>₽</span>
                          </div>
                        </div>
                        <div class="card__btn-container">
                          <!-- (btn) -->

                          <button type="button" class="btn card__btn addCart" data-id="206778" data-price="42890"
                            data-quantity="1" data-name="Планшет Apple iPad mini (2021) 64Gb Wi-Fi Purple"
                            data-picture="/img/apple_ipad_mini__2021__wi_fi_purple_1.jpg">Купить</button>
                          <!-- End (btn) -->
                        </div>
                      </div>
                    </div>
                    <!-- End (card) -->
                  </div>
                  <div class="carousel__item">
                    <!-- (card) -->
                    <div class="card">
                      <div class="card__img-conatiner">
                        <div class="card__sub-buttons">
                          <!-- <label class="card__compare tooltip-compare">
                                      <input type="checkbox" name="compare" value="" class="card__compare-input" data-btn="compare">
                                      <svg class="card__compare-icon">
                                      <use xlink:href="/img//icons.svg#compare-icon"></use>
                                      </svg>
                                  </label> -->
                          <label class="card__favorites tooltip-favorites">
                            <input type="checkbox" name="favorites" value="" class="card__favorites-input"
                              data-btn="favorites">
                            <svg class="card__favorites-icon">
                              <use xlink:href="/img/icons.svg#heart-icon"></use>
                            </svg>
                          </label>
                        </div>
                        <a href="ARRAY(0x557eb2527a68)/besprovodnye_naushniki_apple_airpods_3_magsafe_mme73.html"
                          class="card__img-link">
                          <div class="card__img-wrapper">
                            <img src="/img/apple_airpods_3_1.jpg"
                              alt="Беспроводные наушники Apple AirPods 3 MagSafe (MME73)" class="card__img">
                          </div>
                        </a>
                      </div>
                      <h6 class="h6 card__name">
                        <a href="ARRAY(0x557eb24d37a8)/besprovodnye_naushniki_apple_airpods_3_magsafe_mme73.html"
                          class="card__name-link">Беспроводные наушники Apple AirPods 3 MagSafe (MME73)</a>
                      </h6>
                      <div class="card__footnote">
                        <div class="card__prices">
                          <div class="card__price-current">15890 <span>₽</span>
                          </div>
                        </div>
                        <div class="card__btn-container">
                          <!-- (btn) -->
                          <button type="button" class="btn card__btn addCart" data-id="209807" data-price="15890"
                            data-quantity="1" data-name="Беспроводные наушники Apple AirPods 3 MagSafe (MME73)"
                            data-picture="/img/apple_airpods_3_1.jpg">Купить</button>
                          <!-- End (btn) -->
                        </div>
                      </div>
                    </div>
                    <!-- End (card) -->
                  </div>
                  <div class="carousel__item">
                    <!-- (card) -->
                    <div class="card">
                      <div class="card__img-conatiner">
                        <div class="card__sub-buttons">
                          <!-- <label class="card__compare tooltip-compare">
                                      <input type="checkbox" name="compare" value="" class="card__compare-input" data-btn="compare">
                                      <svg class="card__compare-icon">
                                      <use xlink:href="/img//icons.svg#compare-icon"></use>
                                      </svg>
                                  </label> -->
                          <label class="card__favorites tooltip-favorites">
                            <input type="checkbox" name="favorites" value="" class="card__favorites-input"
                              data-btn="favorites">
                            <svg class="card__favorites-icon">
                              <use xlink:href="/img/icons.svg#heart-icon"></use>
                            </svg>
                          </label>
                        </div>
                        <a href="/catalogue/akusticheskie_sistemy/apple/214145.html" class="card__img-link">
                          <div class="card__img-wrapper">
                            <img src="/img/apple_homepod_mini_blue_1.jpg"
                              alt="Умная колонка Apple HomePod mini Blue" class="card__img">
                          </div>
                        </a>
                      </div>
                      <h6 class="h6 card__name">
                        <a href="/catalogue/akusticheskie_sistemy/apple/214145.html" class="card__name-link">Умная
                          колонка Apple HomePod mini Blue</a>
                      </h6>
                      <div class="card__footnote">
                        <div class="card__prices">
                          <div class="card__price-current">12490 <span>₽</span>
                          </div>
                        </div>
                        <div class="card__btn-container">
                          <!-- (btn) -->
                          <button type="button" class="btn card__btn addCart" data-id="214145" data-price="12490"
                            data-quantity="1" data-name="Умная колонка Apple HomePod mini Blue"
                            data-picture="/img/apple_homepod_mini_blue_1.jpg">Купить</button>
                          <!-- End (btn) -->
                        </div>
                      </div>
                    </div>
                    <!-- End (card) -->
                  </div>
                  <div class="carousel__item">
                    <!-- (card) -->
                    <div class="card">
                      <div class="card__img-conatiner">
                        <div class="card__sub-buttons">
                          <!-- <label class="card__compare tooltip-compare">
                                      <input type="checkbox" name="compare" value="" class="card__compare-input" data-btn="compare">
                                      <svg class="card__compare-icon">
                                      <use xlink:href="/img//icons.svg#compare-icon"></use>
                                      </svg>
                                  </label> -->
                          <label class="card__favorites tooltip-favorites">
                            <input type="checkbox" name="favorites" value="" class="card__favorites-input"
                              data-btn="favorites">
                            <svg class="card__favorites-icon">
                              <use xlink:href="/img/icons.svg#heart-icon"></use>
                            </svg>
                          </label>
                        </div>
                        <a href="/catalogue/iphone/iphone_se__2022/226735.html" class="card__img-link">
                          <div class="card__img-wrapper">
                            <img src="/img/apple_iphone_se_2022_64gb_midnight_1.jpg"
                              alt="Смартфон Apple iPhone SE 2022 128Gb Midnight" class="card__img">
                          </div>
                        </a>
                      </div>
                      <h6 class="h6 card__name">
                        <a href="/catalogue/iphone/iphone_se__2022/226735.html" class="card__name-link">Смартфон Apple
                          iPhone SE 2022 128Gb Midnight</a>
                      </h6>
                      <div class="card__footnote">
                        <div class="card__prices">
                          <div class="card__price-current">39390 <span>₽</span>
                          </div>
                        </div>
                        <div class="card__btn-container">
                          <!-- (btn) -->
                          <button type="button" class="btn card__btn addCart" data-id="226735" data-price="39390"
                            data-quantity="1" data-name="Смартфон Apple iPhone SE 2022 128Gb Midnight"
                            data-picture="/img/apple_iphone_se_2022_64gb_midnight_1.jpg">Купить</button>
                          <!-- End (btn) -->
                        </div>
                      </div>
                    </div>
                    <!-- End (card) -->
                  </div>
                  <div class="carousel__item">
                    <!-- (card) -->
                    <div class="card">
                      <div class="card__img-conatiner">
                        <div class="card__sub-buttons">
                          <!-- <label class="card__compare tooltip-compare">
                                      <input type="checkbox" name="compare" value="" class="card__compare-input" data-btn="compare">
                                      <svg class="card__compare-icon">
                                      <use xlink:href="/img//icons.svg#compare-icon"></use>
                                      </svg>
                                  </label> -->
                          <label class="card__favorites tooltip-favorites">
                            <input type="checkbox" name="favorites" value="" class="card__favorites-input"
                              data-btn="favorites">
                            <svg class="card__favorites-icon">
                              <use xlink:href="/img/icons.svg#heart-icon"></use>
                            </svg>
                          </label>
                        </div>
                        <a href="/catalogue/iphone/iphone_13/228761.html" class="card__img-link">
                          <div class="card__img-wrapper">
                            <img src="/img/apple_iphone_13_midnight_1.jpg"
                              alt="Смартфон Apple iPhone 13 256GB Midnight (A2633)" class="card__img">
                          </div>
                        </a>
                      </div>
                      <h6 class="h6 card__name">
                        <a href="/catalogue/iphone/iphone_13/228761.html" class="card__name-link">Смартфон Apple
                          iPhone 13 256GB Midnight (A2633)</a>
                      </h6>
                      <div class="card__footnote">
                        <div class="card__prices">
                          <div class="card__price-current">65490 <span>₽</span>
                          </div>
                        </div>
                        <div class="card__btn-container">
                          <!-- (btn) -->
                          <button type="button" class="btn card__btn addCart" data-id="228761" data-price="65490"
                            data-quantity="1" data-name="Смартфон Apple iPhone 13 256GB Midnight (A2633)"
                            data-picture="/img/apple_iphone_13_midnight_1.jpg">Купить</button>
                          <!-- End (btn) -->
                        </div>
                      </div>
                    </div>
                    <!-- End (card) -->
                  </div>
                </div>
              </div>
            </div>
            <!-- End (carousel) -->
            <div class="sub-offers__btn-container">
              <!-- (btn) -->
              <a href="#" class="btn sub-offers__btn">Просмотреть все</a>
              <!-- End (btn) -->
            </div>
          </div>
          <div class="sub-offers__info" id="tab-stock">
            <!-- (carousel) -->
            <div class="carousel tab-carousel">
              <div class="carousel__wrapper">
                <div class="carousel__list tab-carousel-list">
                </div>
              </div>
            </div>
            <!-- End (carousel) -->
            <div class="sub-offers__btn-container">
              <!-- (btn) -->
              <a href="#" class="btn sub-offers__btn">Просмотреть все</a>
              <!-- End (btn) -->
            </div>
          </div>
          <div class="sub-offers__info" id="tab-popular">
            <!-- (carousel) -->
            <div class="carousel tab-carousel">
              <div class="carousel__wrapper">
                <div class="carousel__list tab-carousel-list">
                </div>
              </div>
            </div>
            <!-- End (carousel) -->
            <div class="sub-offers__btn-container">
              <!-- (btn) -->
              <a href="#" class="btn sub-offers__btn">Просмотреть все</a>
              <!-- End (btn) -->
            </div>
          </div>
        </div>
      </div>
    </section>
    */?>
    <!-- End (sub-offers) -->

    <!-- (benefits) -->
    <section class="benefits">
      <div class="cover benefits__cover">
        <h3 class="h1 benefits__title">Почему мы лучшие:</h3>
        <div class="benefits__list">
          <div class="benefits__item">
            <div class="benefits__img-container">
              <img src="/img/img_benfit_1.svg" alt="100% качество" class="benefits__img">
            </div>
            <div class="benefits__item-content">
              <h5 class="h3 benefits__item-title">100% качество</h5>
              <p class="benefits__item-text">Все наши товары имеют сертификаты, удостоверяющих их подлинность:
                РостТест, Европейский сертификат качества и EAC.</p>
            </div>
          </div>
          <div class="benefits__item">
            <div class="benefits__img-container">
              <img src="/img/img_benfit_3.svg" alt="Гарантия" class="benefits__img">
            </div>
            <div class="benefits__item-content">
              <h5 class="h3 benefits__item-title">Гарантия</h5>
              <p class="benefits__item-text">Все наши товары имеют гарантию на срок от 12 до 24 месяцев. Также
                доступно расширенное пост-гарантийное обслуживание</p>
            </div>
          </div>
          <div class="benefits__item">
            <div class="benefits__img-container">
              <img src="/img/img_benfit_4.svg" alt="Проверка товара" class="benefits__img">
            </div>
            <div class="benefits__item-content">
              <h5 class="h3 benefits__item-title">Проверка товара</h5>
              <p class="benefits__item-text">Мы всегда даем вам проверить товар до оплаты чтобы вы убедились своими
                глазами, что нам нечего скрывать.</p>
            </div>
          </div>
          <div class="benefits__item">
            <div class="benefits__img-container">
              <img src="/img/img_benfit_2.svg" alt="Самовывоз или доставка"
                class="benefits__img">
            </div>
            <div class="benefits__item-content">
              <h5 class="h3 benefits__item-title">Самовывоз или доставка</h5>
              <p class="benefits__item-text">Наши курьеры осуществляют обычную и экспресс доставку по Москве и
                области. Если вы не хотите ждать ни минуты - приезжайте к нам в офис и забирайте!</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End (benefits) -->


    <!-- (sub-info) -->
    <div class="sub-info">
      <!-- (present) -->
      <section class="present">
        <div class="cover present__cover">
          <h3 class="h1 present__title">Подарок за отзыв!</h3>
          <div class="present__content">
            <div class="present__img-container">
              <img src="/img/present_block.png"
                alt="Мы дарим за отзыв о нашем магазине на Яндекс. Маркете" class="present__img">
            </div>
            <img src="/img/present-symbol.png" alt="" class="present__currency">
            <div class="present__info">
              <h4 class="h2 present__sub-title">Мы дарим за отзыв о нашем магазине на <span>Яндекс.</span> Маркете
              </h4>
              <img src="/img/present-logo.png" alt="" class="present__logo">
              <div class="present__link-container">
                <a href="/otzyvy" class="present__link">Инструкция по получению бонуса</a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- End (present) -->


    </div>
    <!-- End (sub-info) -->


    <!-- (questions) -->
    <section class="questions">
      <div class="cover questions__cover">
        <h3 class="h1 questions__title">Остались вопросы? Мы ответим!</h3>
        <div class="questions__content">
          <div class="questions__form">
            <h5 class="h3 questions__sub-title">Если у вас есть вопросы или предложения, то заполните эту форму:</h5>
            <form action="/system/ajax/" class="b-form-ajax" method="POST">
              <input type="hidden" name="type" value="questions" />
              <div class="questions__form-container">
                <div class="questions__list-fields">
                  <!-- (field) -->
                  <div class="field questions__field">
                    <div class="field__content">
                      <input type="email" name="email" value="" placeholder="Ведите ваш email" class="field__input"
                        autocomplete="off">
                    </div>
                  </div>
                  <!-- End (field) -->
                  <div class="questions__fields-label">или</div>
                  <!-- (field) -->
                  <div class="field questions__field">
                    <div class="field__content">
                      <input type="text" name="telephone" value="" placeholder="Ведите ваш номер телефона"
                        class="field__input field__input_telephone" autocomplete="off">
                    </div>
                  </div>
                  <!-- End (field) -->
                  <!-- (field) -->
                  <div class="field questions__textarea">
                    <div class="field__content">
                      <textarea name="question" placeholder="Напишите ваш вопрос" class="field__textarea"></textarea>
                    </div>
                  </div>
                  <!-- End (field) -->
                </div>
                <div class="questions__policy">Отправляя данную форму вы соглашаетесь с <a href="#">политикой
                    конфиденциальности</a>
                </div>
                <div class="questions__btn-container">
                  <!-- (btn) -->
                  <button type="submit" name="submit" class="btn questions__btn">Спросить</button>
                  <!-- End (btn) -->
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- End (questions) -->
    
    <?=$this->render('../_blocks/nt_footer')?>

  </div>
</div>

</div>
<!-- End (wrapper) -->


