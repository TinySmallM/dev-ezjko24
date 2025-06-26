<?
$this->registerMetaTag(['name' => 'description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => $data['description']]);
$this->title = $data['title'];

$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js');

$this->registerCss('
.review-item {
    border: 2px solid #212529;
    display: flex;
    flex-direction: column;
    position: relative;
  }
  .review-item .review-header {
    display: flex;
    justify-content: center;
  }
  .review-item .review-header > span {
    display: block;
    padding: 2px 30px 6px;
    background-color: #212529;
    color: white;
    clip-path: polygon(100% 0%, 90% 50%, 100% 100%, 0 100%, 10% 50%, 0 0);
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
    background-color: #212529;
    color: white;
    padding: 0px 5px 5px;
  }
  .review-item .review-content .images div{
    width: 60px;
    height: 60px;
    background-size: cover;
    background-position: center;
    margin-right: 4px;
    display: inline-block;
  }
  .review-item .review-content .workCard{
      margin-top: 10px;
  }
  .review-item .review-client {
    padding: 5px 25px;
    //display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .review-item .review-client .name {
    text-transform: uppercase;
    font-size: 18px;
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
    background-color: #212529;
    font-size: 35px;
    color: white;
    padding: 0px 15px 2px;
    margin-right: 3px;
  }
  .review-item .star-container .stars {
    background-color: #212529;
    color: white;
    font-size: 20px;
    padding: 3px;
  }
  @media (max-width: 991px) {
    .review-item .star-container {
      position: initial;
    }
  }
');
?>

<section id="pageheader">
	<div class="container">
		<h1><?=$this->title;?></h1>
	</div>
</section>
<div class="container categories mt-5">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
            <?foreach($data['items'] as $d){
            	if($d['regionId'] && $d['regionId'] != Yii::$app->params['region']['current']['id']) continue;
                $dCreated = date_create_from_format('U',$d['dateCreated']);
                $dArr = [1=>'янв','февр','март','апр','май','июнь','июль','авг','сен','окт','ноя','дек'];
                ?>
            <div class="review-item mb-5">
                <div class="review-header">
                    <span>Договор № <?=$d['doc']?></span>
                </div>
                <div class="star-container">
                    <span class="evaluation"><?=$d['stars']?></span>
                    <span class="stars">
                        <?for($i=0;$i<5;$i++){
                            $yellow = '';
                            if($d['stars'] >= $i+1) $yellow = 'color: #f3cb00;';
                            ?>
                        <i class="fa fa-star" style="<?=$yellow?>"></i>
                        <?}?>
                    </span>
                </div>
                <div class="review-content">
                    <div class="date">
                        <span class="day"><?=$dCreated->format('d')?></span>
                        <span class="month"><?=$dArr[$dCreated->format('n')]?></span>
                        <span class="year"><?=$dCreated->format('Y')?></span>
                    </div>
                    <div class="review-client">
                        <div class="name mb-2"><?=$d['fio']?></div>
                        <div class="text"><?=$d['text']?></div>
                        <?/*
                        <div class="images">
                            <?foreach($d['image'] as $im_i=>$im_d){?>
                            <a href="/upload/<?=$im_d?>" data-fancybox="review_<?=$d['id']?>">
                                <div style="background-image: url(/upload/thumb_<?=$im_d?>)"></div>
                            </a>
                            <?}?>
                        </div>
                        */?>
                        <?if($d['product']){?>
                            <div class="workCard">
                            Карточка работы: <a href="/<?=$d['product']['url']?>/"><?=$d['product']['menuname']?></a>
                            </div>
                        <?}?>
                    </div>
                </div>
            </div>
            <?}?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
			<h2 class="title" style="margin-top:0px">Отправить отзыв</h2>
		</div>
    </div>
    
    <form>
		<div class="row">
    		<div class="form-group col-md-6">
    			<input type="text" class="form-control" name="reviewname" placeholder="Ваше имя *" required>
    		</div>
		    <div class="form-group col-md-6">
			      <input type="text" class="form-control" name="reviewnumber" placeholder="№ договора" required>
		    </div>
		</div>
		<div class="row my-5">
			<div class="form-group col-md-12">
				<textarea class="form-control" name="reviewmess" rows="5" required style="max-width:100%">Ваш отзыв</textarea>
			</div>
		</div>
		<button type="submit" class="btn btn-primary mb-5">Отправить</button>
	</form>
</div>
