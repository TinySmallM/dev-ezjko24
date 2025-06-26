<?
$this->registerMetaTag(['name' => 'description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => $data['description']]);
$this->title = $data['title'];

//$this->registerJsFile('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js');
//$this->registerCssFile('https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css',['position'=>yii\web\View::POS_END]);

$this->registerCss('
.static__banner_h1 {
  text-transform: uppercase;
  font-weight: 600;
  font-size: 2.8em;
  margin-bottom: 20px;
  color: #222;
  padding-top: 115px;
}
.review-item {
  display: flex;
  flex-direction: column;
  position: relative;
  background: #f6f7f8;
  border-radius: 5px;
  box-shadow: 0;
  box-shadow: 0 0 11px #bababa;
  }
  .review-item .review-header {
    display: flex;
    justify-content: center;
  }
  .review-item .review-header > span {
    display: block;
    padding: 6px 44px 8px;
    background-color: #6c6c6c;
    color: white;
    clip-path: polygon(100% 0%, 90% 50%, 100% 100%, 0 100%, 10% 50%, 0 0);
    font-size: 1rem;
  }
  .review-item .review-content {
    display: flex;
    padding: 15px;
  }
  .review-item .review-content .date {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 30px;
  }
  .review-item .review-content .date .day {
    font-weight: bold;
    color: #212529;
  }
  .review-item .review-content .date .month {
    background-color: #21342f;
    color: white;
    padding: 0px 5px 5px;
  }
  .review-item .review-content .images{
    display: flex;
    column-gap: 10px;
  }
  .review-item .review-content .images div{
    width: 100%;
    height: 100%;
    max-width: 200px;
    max-height: 200px;
    background-size: cover;
    background-position: center;
    margin-right: 4px;
    display: inline-block;
  }
  .review-item .review-content .images img{
    width: 100%;
    height: 100%;
    max-width: 340px;
    border-radius: 5px;
  }
  .review-item .review-content .workCard{
      margin-top: 10px;
  }
  .review-item .review-client {
    padding: 5px 25px;
    color: #333;
    font-size: 1rem;
  }
  .review-item .review-client .name {
    text-transform: uppercase;
    font-weight: 600;
  }
  .review-item .review-client .text {
    font-size: 15px;
  }
  .review-item .star-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    float: right;
    right: 0px;
    top: 25px;
    margin: 5px 0px;
  }
  .review-item .star-container .evaluation {
    background-color: #6c6c6c;
    font-size: 35px;
    color: white;
    padding: 0px 15px 2px;
    margin-right: 3px;
  }
  .review-item .star-container .stars {
    color: white;
    font-size: 20px;
    padding: 3px;
    padding-bottom: 6px;
    padding-top: 0;
  }
  .review-item .star-container .stars img{
    width: 26px;
  }

  .splide_reviews img{
    max-width: 250px;
    max-wheight: 250px;
  }
  @media (max-width: 991px) {
    .review-item .star-container {
      position: initial;
    }
  }

  @media (max-width: 460px) {
    .static__banner_h1{
      padding-top: 90px;
    }
    .review-item .star-container{
      order: 2;
      margin-bottom: 20px;
    }
    .review-item .review-content .images img{
      max-width: 110px;
    }
  }
');
?>


<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
    <?=$this->render('../_blocks/nt_header')?> 

    <div class="static__index cover">
      <div class="container mt-5 d-flex flex-hor-center flex-dir-col flex-vert-center">
          <h1 class="static__banner_h1 text-center">Отзывы</h1>

          <?/*
          <div style="width: 100%;max-width:760px;height:800px;overflow:hidden;position:relative;"><iframe style="width:100%;height:100%;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box" src="https://yandex.ru/maps-reviews-widget/189925853526?comments"></iframe><a href="https://yandex.ru/maps/org/kursy_dlya_studentov_medikov/8220179633/" target="_blank" style="box-sizing:border-box;text-decoration:none;color:#b3b3b3;font-size:10px;font-family:YS Text,sans-serif;padding:0 20px;position:absolute;bottom:8px;width:100%;text-align:center;left:0;overflow:hidden;text-overflow:ellipsis;display:block;max-height:14px;white-space:nowrap;padding:0 16px;box-sizing:border-box">Курсы для студентов медиков на карте Москвы — Яндекс Карты</a></div>
          */?>
      </div>
    </div>

    <div class="container cover categories mt-5">
      <h2 class="text-center">ОТЗЫВЫ В СОЦ СЕТЯХ</h2>
        <div class="row">
            <div class="col-12 col-md-10 offset-md-1">
                <?foreach($data['items'] as $d){
                  if($d['regionId'] && $d['regionId'] != Yii::$app->params['region']['current']['id']) continue;
                    $dCreated = date_create_from_format('U',$d['dateCreated']);
                    $dArr = [1=>'янв','февр','март','апр','май','июнь','июль','авг','сен','окт','ноя','дек'];
                    ?>
                <div class="review-item mb-5">
                    <div class="review-header">
                        <span>
                          <?=$dCreated->format('d')?> <?=$dArr[$dCreated->format('n')]?> <?=$dCreated->format('Y')?><br>
                        </span>
                    </div>
                    <div class="star-container">
                        <span class="evaluation"><?=$d['stars']?></span>
                        <span class="stars">
                            <?for($i=0;$i<5;$i++){
                                $yellow = '';
                                if($d['stars'] >= $i+1) $yellow = 'color: #f3cb00;';
                                ?>
                            <img src="/img/star-icon.svg" style="<?=$yellow?>">
                            <?}?>
                        </span>
                    </div>
                    <div class="review-content">
                      <?/*
                        <div class="date">
                            <span class="day"><?=$dCreated->format('d')?></span>
                            <span class="month"><?=$dArr[$dCreated->format('n')]?></span>
                            <span class="year"><?=$dCreated->format('Y')?></span>
                        </div>
                        */?>
                        <div class="review-client">
                            <div class="name mb-2"><?=$d['fio']?>:</div>
                          <?/* <div class="text"><?=$d['text']?></div> */?>
                            <div class="images">
                                <?foreach($d['image'] as $im_i=>$im_d){?>
                                <a href="/upload/<?=$im_d['image']?>" data-fancybox="review_<?=$d['id']?>">
                                    <!--<div style="background-image: url(/upload/thumb_<?=$im_d['image']?>"></div>-->
                                    <img src="/upload/thumb_<?=$im_d['image']?>">
                                </a>
                                <?}?>
                            </div>
                            <?if($d['doc']){?>
                                <div class="workCard">
                                <?=$d['doc']?>
                                </div>
                            <?}?>
                        </div>
                    </div>
                </div>
                <?}?>
            </div>
        </div>

        

    </div>

  <?=$this->render('../_blocks/nt_footer')?>
  
  </div>

</div>

<?
$script = <<< JS

  new Splide( '.splide', {
    type   : 'loop',
    perPage: 3,
    autoplay: true,
    interval: 5000,
    pauseOnHover: false
  } ).mount();

JS;
$this->registerJs($script, yii\web\View::POS_END);
?>