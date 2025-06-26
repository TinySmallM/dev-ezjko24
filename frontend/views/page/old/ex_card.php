<?

use common\models\ServPredicate;

use common\models\Helpers;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::home(true).'upload/'.$data['image'] ]);
$this->title = Helpers::phReplace($data['title']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->registerCss('
.product-price-sum {
font-size: 2.5rem;
font-weight: bold;
color:#fa7d09;
}
.product-price-sum-min {
font-weight: bold;
color:#fa7d09;
}
.product-price-field {

	display: block;
line-height: 45px;
}
.product-price-field-2 {
	font-size: .9rem;
	display: block;
line-height: 0px;
}
');

$sp = ServPredicate::find()->asArray()->all();
$sp = ArrayHelper::index($sp, 'id');
?>




<!-- Product block -->
<section id="pageheader">
	<div class="container">
		<h1><?=Helpers::phReplace($data['h1'])?></h1>
		<!-- хлебные крошки -->
		<?if( isset($_SESSION['category']) ){?>
	    	<a href="/<?=end($_SESSION['category'])['url']?>/"><?=end($_SESSION['category'])['menuname']?></a>&nbsp;/&nbsp;<a href="javascript:void(0)"><?=Helpers::phReplace($data['h1'])?></a>
	    	<?}?>
	</div>
</section>

<div class="subheader">
	<div class="row no-gutters">
		<div class="col-md-6">		
				<div class="description wow fadeInLeft">						
					<ol class="characteristic">
						<?foreach($data['chunk']['services1']['content'] as $i=>$d){?>
							<?$nameProd = str_replace('- ', '', $d['name']);?>
							<li>
								<span><?=$nameProd?></span>
								<span class="price"><?=number_format($d['price'], 0, ',', ' ')?>&nbsp;руб.</span></li>
							<li>
						<?}?>
						</ol>
					<span class="price-big">Итого: <?= number_format($data['price2_sum'], 0, ',', ' ')?>&nbsp;руб.</span>
				</div>	
		</div>
		<div class="col-md-6">
			<div class="image wow fadeInUp" style="background-image: url(/upload/thumb_<?=$data['gallery'][0]['image'];?>);height: 400px;background-size: cover;position: relative;
top: -60px;">
			</div>
			<div class="wow fadeInUp">
				<?=Helpers::phReplace($data['content'])?>
			</div>
		</div>
	</div>

</div>
<!-- Остались вопросы? -->
<?=$this->render('../_blocks/form-1')?>
<?=$this->render('../_blocks/lastviewed')?>