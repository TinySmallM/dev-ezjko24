<?

use common\models\Page;

$items_categories = Page::find()
    ->where(['id'=>[57,58,59,60,65,70,74,89,123,124,125,126],'published'=>1])
    ->andWhere(['!=', 'image', 'NULL'])
    ->limit(8)
    ->orderBy(['sortId'=>SORT_ASC])
    ->asArray()->all();

$items_categories_all = Page::find()
    ->where(['template'=>[2,3],'published'=>1])
    ->orderBy(['sortId'=>SORT_ASC])
    ->asArray()->all();

?>

<div class="wrapper__head js-head">
        <!-- (header) -->
        <header class="header">
          <div class="header__cover">
            <div class="header__wrapper">
              <div class="header__logo">
                <a href="/" class="header__logo-link">
                  <img src="/re/re-logo.svg" alt="Рус электроника" class="header__logo-img">
                </a>
              </div>
              <div class="header__contacts">
                <div class="header__telephones">
                  <div class="header__telephone-item">
                    <a href="tel:+79165546394" class="header__telephone-link" style="display: flex"><div style="width: 100px">Розница</div> +7 916 554 63 94 </a>
                  </div>
                  <div class="header__telephone-item">
                    <a href="tel:+79165546394" class="header__telephone-link" style="display: flex"><div style="width: 100px">Оптом</div> +7 916 554 63 94 </a>
                  </div>
                  <div class="header__telephone-item">
                    <a href="mailto:info@rus-electronika.ru" class="header__telephone-link" style="display: flex">info@rus-electronika.ru</a>
                  </div>
                </div>
                <div class="header__call">
                  <a href="tel:+74992137786" class="header__call-link">
                    <svg class="header__call-icon">
                      <use xlink:href="img/icons.svg#telephone-icon"></use>
                    </svg>
                    <div class="header__call-label">Перезвоните мне</div>
                  </a>
                </div>
                <ul class="header__networks">
                  <li class="header__network-item">
                    <a href="https://www.instagram.com/" target="_blank" class="header__network-link">
                      <svg class="header__network-icon header__network-icon_instagram">
                        <use xlink:href="img/icons.svg#instagram-icon"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="header__network-item">
                    <a href="https://wa.me/79165546394" target="_blank" class="header__network-link">
                      <svg class="header__network-icon header__network-icon_whatsapp">
                        <use xlink:href="img/icons.svg#whatsapp-icon"></use>
                      </svg>
                    </a>
                  </li>
                  <!-- <li class="header__network-item">
                    <a href="#" target="_blank" class="header__network-link">
                      <svg class="header__network-icon header__network-icon_facebook">
                        <use xlink:href="/img/icons.svg#facebook-icon"></use>
                      </svg>
                    </a>
                  </li> -->
                  <li class="header__network-item">
                    <a href="https://t.me/rus_electronika" target="_blank" class="header__network-link">
                      <svg class="header__network-icon header__network-icon_telegram">
                        <use xlink:href="img/icons.svg#telegram-icon"></use>
                      </svg>
                    </a>
                  </li>
                </ul>
                <div class="header__mode">
                  <h6 class="h6 header__mode-title">Режим работы:</h6>
                  <ul class="header__mode-list">
                    <li class="header__mode-item">пн-пт: 10:00–20:00</li>
                    <li class="header__mode-item">сб-вс: 11:00–18:00</li>
                  </ul>
                </div>
              </div>
              <div class="header__btn-container">
                <div class="header__btn-wrapper">
                  <button class="header__btn header__btn_search" id="search-btn">
                    <svg class="header__btn-icon header__btn-icon_search">
                      <use xlink:href="img/icons.svg#search-icon"></use>
                    </svg>
                  </button>
                  <div class="header__form-search" id="form-search">
                    <div class="header__form-search-wrapper">
                      <form action="/search/" method="get">
                        <input type="text" name="text" value="" placeholder="введите запрос" class="header__form-input"
                          autocomplete="off">
                      </form>
                    </div>
                  </div>
                </div>
                <!-- <div class="header__btn-wrapper">
                  <a href="#" class="header__btn header__btn_compare">
                    <svg class="header__btn-icon header__btn-icon_compare">
                      <use xlink:href="/img/icons.svg#compare-icon"></use>
                    </svg>
                  </a>
                </div> -->
                <!-- <div class="header__btn-wrapper">
                  <a href="#" class="header__btn header__btn_favorites">
                    <svg class="header__btn-icon header__btn-icon_favorites">
                      <use xlink:href="/img/icons.svg#heart-icon"></use>
                    </svg>
                  </a>
                </div> -->
                <div class="header__btn-wrapper">
                  <a href="/cart" class="header__btn header__btn_basket">
                    <svg class="header__btn-icon header__btn-icon_basket">
                      <use xlink:href="img/icons.svg#basket-icon"></use>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </header>
        <!-- End (header) -->
        <div class="wrapper__stub js-stub"></div>
        <div class="wrapper__float js-panel">
          <!-- (nav) -->
          <div class="nav">
            <div class="nav__wrapper">
              <div class="cover nav__cover">
                <div class="nav__pull openMenu" id="btn-pull">
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
                <ul class="nav__list">
                  <li class="nav__item">
                    <a href="/" class="nav__link" id="nav-catalog">Каталог</a>
                    <div class="nav__category-wrapper category-wrapper" id="category-wrapper">
                      <div class="nav__category-section category-section">
                        <ul class="nav__list-categories list-categories" id="category-1-list-1">

                          <?foreach($items_categories_all as $i=>$d){
                            if($d['parent']) continue;
                            ?>
                            <li class="nav__category-item category-item" data-src="#category-2-list-<?=$d['id']?>">
                              <a href="/<?=$d['url']?>" class="nav__category-link">
                                <div class="nav__category-name"><?=$d['h1']?></div>
                              </a>
                            </li>
                          <?}?>

                        </ul>
                      </div>
                      <div class="nav__category-section category-section">
                          <?foreach($items_categories_all as $i=>$d){
                            if($d['parent']) continue;
                            ?>
                            <ul class="nav__list-categories list-categories" id="category-2-list-<?=$d['id']?>">
                              <li class="nav__category-item category-item">
                                <a href="/<?=$d['url']?>" class="nav__category-link">
                                  <div class="nav__category-name"><?=$d['h1']?></div>
                                </a>
                                <ul class="nav__list-categories list-categories">
                                  <?foreach($items_categories_all as $i_i=>$d_d){
                                    if($d_d['parent'] != $d['id']) continue;
                                    ?>
                                    <li class="nav__category-item">
                                      <a href="/<?=$d_d['url']?>" class="nav__category-link">
                                        <div class="nav__category-name"><?=$d_d['h1']?></div>
                                      </a>
                                    </li>
                                  <?}?>

                                </ul>
                              </li>

                            </ul>
                          <?}?>

                      </div>
                    </div>
                  </li>
                  <li class="nav__item">
                    <a href="/kontakty" class="nav__link">О магазине</a>
                  </li>

                  <li class="nav__item">
                    <a href="/kontakty" class="nav__link">Контакты</a>
                  </li>

                  <!--<li class="nav__item">
                    <a href="/warranty" class="nav__link">Гарантии</a>
                  </li>

                  <li class="nav__item">
                    <a href="/delivery" class="nav__link">Доставка и оплата</a>
                  </li>!-->
                </ul>
              </div>
            </div>
          </div>
          <!-- (categories) -->
          <div class="categories">
            <div class="categories__cover">
              <div class="categories__wrapper">
                <div class="categories__list">
                <?foreach($items_categories as $i=>$d){?>
                  <div class="categories__item">
                    <a href="/<?=$d['url']?>" class="categories__link">
                      <div class="categories__img-container">
                        <img src="/upload/<?=$d['image'] != 'NULL'?$d['image']:$d['product'][0]['image']?>" alt="<?=$d['h1']?>" class="categories__img">
                      </div>
                      <div class="categories__name"><?=$d['h1']?></div>
                    </a>
                  </div>
                <?}?>
                </div>
              </div>
            </div>
          </div>
          <!-- End (categories) -->
          <!-- (categories) -->
        </div>
      </div>