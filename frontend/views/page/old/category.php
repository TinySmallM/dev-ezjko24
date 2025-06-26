<?
use common\models\Helpers;
use yii\helpers\Url;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::home(true).'upload/'.$data['image'] ]);
$this->title = Helpers::phReplace($data['title']);

?>



<section id="pageheader">
	<div class="container">
		<h1><?=Helpers::phReplace($data['h1'])?></h1>
	</div>
</section>

<div class="subheader">
	<div class="row no-gutters">
		<div class="col-md-6">		
				<div class="description wow fadeInLeft">
					<?=Helpers::phReplace($data['chunk']['text1']['content'])?>
				</div>	
		</div>
		<div class="col-md-6">
			<div class="wow fadeInUp" style="background-image: url(<?=Url::home(true).'upload/'.$data['image']?>);min-height:100%;top:-60px;position:relative;background-size: cover;">

			</div>
		</div>
	</div>
</div>

<!-- Плитка -->
<?=$this->render('../_blocks/ex_card_tile',['data'=>[
    'product'=>$data['product'],
    'h2'=>Helpers::phReplace($data['chunk']['exTileh2_1']['content']),
    'text1'=>'',
    'text2'=>'',
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])?>


<!-- Остались вопросы? -->
<?=$this->render('../_blocks/form-1')?>

<section class="section about">
	<div class="row no-gutters">
		<div class="col-md-6">
			<div class="image wow fadeInLeft" style="background-image: url(<?=$data['chunk']['text2_img']['content']?'/upload/'.$data['chunk']['text2_img']['content']:'/img/dest/about-windows.png'?>);">
			</div>
		</div>
		<div class="col-md-6">
			<div class="description-wrapper">					
				<div class="description description-left wow fadeInRight" style="max-width: 600px; padding-top: 25px;">
					<?=Helpers::phReplace($data['chunk']['text2']['content'])?>
				</div>		
			</div>
		</div>
	</div>
</section>


<!-- Плитка категорий -->
<?=$this->render('../_blocks/ex_category_tile',['data'=>[
    'product'=>$data['child'],
    'h2'=>'',
    'text1'=>'',
    'text2'=>'',
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])?>

<!-- Как сделать заказ -->
<?=$this->render('../_blocks/howdoit')?>

<!-- Остались вопросы? -->
<?=$this->render('../_blocks/form-1')?>