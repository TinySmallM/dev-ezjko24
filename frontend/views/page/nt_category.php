<?php

use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1'])]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
if($data['image']) $this->registerMetaTag(['property' => 'og:image', 'content' => '/upload/'.$data['image']]);

$this->title = Helpers::phReplace($data['title'].' – купить');

$this->registerCss("

  .btn-disabled {
    color:#FFF !important; 
    position: relative; 
    font-size:13px; 
    cursor: pointer; 
    display:inline-block; 
    text-decoration:none !important; 
    border: 1px solid #B3B3B3;
    background: #000;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4D4D4D', endColorstr='#000000');
    background: -webkit-gradient(linear, left top, left bottom, from(#4D4D4D), to(#000));
    background: -moz-linear-gradient(top,  #4D4D4D,  #000); vertical-align: middle;
  }

  .b-blue-button.cart {
    padding-left: 40px; font-size:15px;
  }
  .b-blue-button.cart img {
    position: absolute; 
    left:10px; 
    top:4px; 
    vertical-align: middle;
    }


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

  <!-- (catalog) -->

  <?if($data['child']){?>
  <section class="main-catalog">
      <div class="cover main-catalog__cover">
        <h1 class="h1 main-catalog__title"><?=$data['h1']?></h1>
        <div class="main-catalog__content">
          <div class="main-catalog__section">
            <h4 class="h2 main-catalog__sub-title">
              <a href="" class="main-catalog__sub-title-link">Наши товары</a>
            </h4>
            <div class="main-catalog__section-content">
              <div class="main-catalog__list">
              <?foreach($data['child'] as $i=>$d){
                
                if( (!$d['image'] || $d['image'] == 'NULL') && $d['product']) {
                  foreach($d['product'] as $p_i=>$p_d){
                    if($p_d['image'] && $d['image'] != 'NULL') $d['image'] = $d['product'][0]['image'];
                    
                  }
                }
                
                ?>
                <div class="main-catalog__category">
                  <a href="/<?=$d['url']?>" class="main-catalog__category-link">
                    <h6 class="h4 main-catalog__category-name"><?=$d['title']?></h6>
                    <div class="main-catalog__category-img-container">
                      <img src="/upload/thumb_<?=$d['image']?>" alt="<?=$d['h1']?>"
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
  <?}?>

  <?if($data['product']){?>
  <section class="catalog">
    <div class="cover catalog__cover">
      <h1 class="h1 catalog__title"><?=$data['h1']?></h1>

      <div class="catalog__wrapper clearfix">
        <div class="catalog__content">
          <div class="catalog__sort-wrapper">
            <div class="catalog__filter-btn-container">
              <button type="button" class="btn catalog__filter-btn" data-src="#filter" id="btn-filter-open">
                <span class="catalog__filter-btn-text">
                  <svg class="catalog__filter-btn-icon">
                    <use xlink:href="/img/icons.svg#icon-filter"></use>
                  </svg>
                  Фильтр
                </span>
              </button>
            </div>
            <div class="catalog__sort-dropdown">
              <div class="catalog__sort-dropdown-label">Сортировка:</div>
              <div class="catalog__sort-dropdown-select catalog__sort-dropdown-select_sort">
                  <select class="filters_arr filters_apply_on_change" name="sort[price1_sum]">
                    <option value="asc">Цена (низкая > высокая)</option>
                    <option value="desc" >Цена (высокая > низкая)</option>
                  </select>
              </div>
            </div>
            <div class="catalog__sort-sub-container">
              <div class="catalog__sort-dropdown" >
                <div class="catalog__sort-dropdown-label">Показать:</div>
                <div class="catalog__sort-dropdown-select catalog__sort-dropdown-select_count">
                    <select class="filters_arr filters_apply_on_change" name="count">
                      <option value="12" >12</option>
                      <option value="24" >24</option>
                      <option value="36" >36</option>
                    </select>
                </div>
              </div>
              <div class="catalog__count">Найдено:&nbsp;&nbsp;&nbsp;<?=count($data['product'])?> шт</div>
            </div>
          </div>

          <div class="catalog__list">

            <?foreach($data['product'] as $i=>$d){?>
            <div class="catalog__item">
                <!-- (card) -->
                <div class="card card_small">
                <div class="card__img-conatiner">
                    <a href="/<?=$d['url']?>" class="card__img-link">
                    <div class="card__img-wrapper">
                        <img src="/upload/thumb_<?=$d['image']?>" alt="<?=$d['h1']?>" class="card__img">
                    </div>
                    </a>
                </div>
                <h6 class="h6 card__name">
                    <a href="/<?=$d['url']?>" class="card__name-link"><?=$d['h1']?></a>
                </h6>
                <?if($d['price1_sum']){?> 
                <div class="card__footnote">
                    <div class="card__prices">
                    <div class="card__price-current"><?=number_format($d['price1_sum'], 0, ',', ' ')?><span>₽</span>
                    </div>
                    </div>
                    
                    <div class="card__btn-container">
                    <!-- (btn) -->
                    
                    <button type="button" class="btn card__btn addCart" data-buypricetype="1" data-buyid="<?=$d['id']?>" data-price="<?=$d['price1_sum']?>" href="javascript:void(0)">Купить</button>
                    
                    <!-- End (btn) -->

                    </div>
                    
                </div>
                <?}?>
                </div>
                <!-- End (card) -->
            </div>
            <?}?>


          </div>


        </div>

        <div class="catalog__sidebar" id="catalog__sidebar">
          <!-- (filter) -->
          <form id="filter">
            <input type="hidden" name="fullurl" value="/catalogue/iphone" />
          <div class="filter catalog__filter" id="filter">
            <div class="filter__header">
              <div class="h3 filter__title">Фильтр товаров</div>
              <button class="filter__btn-cross" data-fancybox-close>
                <svg class="filter__btn-icon-cross">
                  <use xlink:href="/img/icons.svg#icon-cross"></use>
                </svg>
              </button>
            </div>
            <div class="filter__content">
              <div class="filter__section active">
                <div class="h4 filter__section-name">
                  Цена
                  <div class="filter__section-name-icon">
                    <svg class="filter__section-name-arrow">
                      <use xlink:href="/img/icons.svg#blunt-arrow-horizontal"></use>
                    </svg>
                  </div>
                </div>
                <div class="filter__section-content">
                  <div class="filter__range">
                    <!-- (range) -->
                    <div class="range">
                      <div class="range__fields">
                        <!-- (field) -->
                        <div class="field range__field-item">
                          <div class="field__content">
                            <input type="text" name="filters[price1_sum][from]" value="" placeholder="" autocomplete="off"
                              class="field__input range__field-input filters_arr" data-range-name="from">
                          </div>
                          <div class="range__field-currency">₽</div>
                        </div>
                        <!-- End (field) -->
                        <div class="range__fields-line"></div>
                        <!-- (field) -->
                        <div class="field range__field-item">
                          <div class="field__content">
                            <input type="text" name="filters[price1_sum][to]" value="" placeholder="" autocomplete="off"
                              class="field__input range__field-input filters_arr" data-range-name="before">
                          </div>
                          <div class="range__field-currency">₽</div>
                        </div>
                        <!-- End (field) -->
                      </div>
                      <div class="range__slider-container">
                        <input type="text" name="price" value="" placeholder="" autocomplete="off" class="range__slider"
                          data-from="2000" data-before="500000" data-min="2000" data-max="500000">
                      </div>
                    </div>
                    <!-- End (range) -->
                  </div>
                </div>
              </div>
              
              <?foreach($data['charactsfields'] as $ch_key=>$ch_d){
                if($ch_d['type'] != 'select') continue;
                ?>
              <div class="filter__section active">
                <div class="h4 filter__section-name">
                  <?=$ch_d['nameRu']?>:
                  <div class="filter__section-name-icon">
                    <svg class="filter__section-name-arrow">
                      <use xlink:href="/img/icons.svg#blunt-arrow-horizontal"></use>
                    </svg>
                  </div>
                </div>
                <div class="filter__section-content">
                  <select class="form-control filters_arr" name="ch[<?=$ch_d['name']?>]">
                    <option selected value="">Все</option>
                    <?foreach($ch_d['options'] as $opt){?>
                    <option value="<?=$opt?>"><?=$opt?></option>
                    <?}?>
                  </select>
                </div>
              </div>
              <?}?>



              <button type="button" class="btn btn-sm apply_filters">Применить</button>
            </div>
          </div>
          </form>
          <!-- End (filter) -->
        </div>

      </div>
    </div>
  </section>
  <?}?>

  <?=$this->render('../_blocks/nt_footer')?>

  </div>
</div>



<?
$script = <<< JS

$('.filters_arr').each(function(el){
  let params = new URLSearchParams(window.location.search);
  if( params.get( $(this).attr('name') ) ) $(this).val( params.get( $(this).attr('name') ) );
})

$('.filters_apply_on_change').change(function(){
  applyFilters();
})
$('.apply_filters').click(function(){
  applyFilters();
});

function applyFilters(){
  let arr_d = {}

  $('.filters_arr').each(function(el){
    if( $(this).val() ) arr_d[$(this).attr('name')] = $(this).val();
  })

  console.log(arr_d);

  let params = '?';
  Object.keys(arr_d).forEach(function(key){
    params += key+'='+arr_d[key] + '&';
  })

  window.location.href = window.location.pathname + params;
}

$('.catalog__filter-btn').click(function(){
  scrollToFilters();
})

function scrollToFilters(){
  let element = document.getElementById('catalog__sidebar');
  let headerOffset = 100;
  let elementPosition = element.getBoundingClientRect().top;
  let offsetPosition = elementPosition + window.pageYOffset - headerOffset;
  window.scrollTo({
        top: offsetPosition,
        behavior: "smooth"
  });
}

JS;
$this->registerJs($script, yii\web\View::POS_READY);  

?>