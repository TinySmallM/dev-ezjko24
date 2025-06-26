<?

use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1'])]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) . Helpers::phReplace($data['h1']) ]);
if($data['image']) $this->registerMetaTag(['property' => 'og:image', 'content' => 'https://atego36.ru/upload/'.$data['image']]);

$this->title = Helpers::phReplace($data['title'].' – купить в магазине Атего36');

$this->registerCss('

	.static__banner_h1{
		text-transform: uppercase;
		font-weight: 600;
		font-size: 1.8rem;
		margin-bottom: 20px;
		color: #222;
		padding-top: 115px;
	}

	.product_line_1{
		display: flex;
		width: 100%;
	}
	.product_img_block,
	.product_price_block{
		width: 50%;
	}
	.product_img_block{
		background: #c3c3c3;
	}
	.product_price_block{
		padding: 20px;
		background: #fff;
	}


	@media (max-width: 429px) {
		.static__banner_h1{
			font-size: 1.4rem;
		}
	}

    .atego_desc{
        color: #222;
		font-size: 0.9rem;
    }
    .atego_desc h2{
        font-weight: 600;
        font-size: 1.5rem;
    }

	.product-price-field{
		color: #222;
		font-size: 1.1rem;
	}

	.atego_desc ul{
		list-style-type: square;
		padding: 0 15px;
	}
	.fotorama__wrap{
		background: #ffffff;
	}


');

?>

<?=$this->render('../_blocks/header',[
	'class'=>'navbar_light',
	'type'=>'index',
])?>

<main>

<!-- Product block -->
<div class="container product-page">
    <div class="row" itemscope itemtype="http://schema.org/Product">
    	<!--
        <div class="col-12">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="/">Главная страница</a></li>
				<li class="breadcrumb-item"><a href="/aromatizatory/">Ароматизаторы</a></li>
				<li class="breadcrumb-item active">Ароматизатор Румбарум (ромовый) 3кг</li>
			</ol>
        </div>
        -->

		<div class="col-12" itemprop="name">
			<h1 class="static__banner_h1"><?=$data['h1']?></h1>
		</div>

		<div class="col-12">
			<div class="product_line_1 mob-flex-dir-col">
			
				<!-- Image -->
				<div class="product_img_block mob-width-100">
					<div style="border: 1px solid #f7f7f7;">
						<div class="fotorama" data-width="100%" data-ratio="1000/600" data-nav="thumbs" data-allowfullscreen="true" data-thumbwidth="30" data-thumbheight="30">
							
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
				</div>
				
				<!-- Price -->
				<div class="product_price_block mob-width-100"  itemprop="offers" itemscope itemtype="http://schema.org/Offer">

					<link itemprop="availability" href="http://schema.org/InStock">

					<h6 class="mb-0 mt-md-4" style="font-weight:600;color:#222;font-size: 1.3rem;">Цена: </h6>
					
					<?if($data['price1_sum']){?>
						<meta itemprop="price" content="<?=$data['price1_sum']?>">
						<meta itemprop="priceCurrency" content="RUB">
						
						<span class="product-price-field">
							<?=$data['price1_sum']?> руб. за <?=$data['price1_name']?>
						</span>
					<?}else{?>
						<span class="product-price-field">по запросу</span>
					<?}?>
			
					<form class="cartAdd" style="margin-top: 20px;">
						<input type="hidden" name="id" value="<?=$data['id']?>">
						<input type="hidden" name="priceType" value="1">
						<input type="hidden" name="count" value="1">
						<div class="form-group">
							<button class="btn btn-christ" type="submit">Добавить в корзину</button>
						</div>
					</form>    
				</div>

			</div>
		</div>
        
        <!-- About -->
        <?if($data['content']){?>
    	<div class="col-md-12 mt-5 ">
			<div class="atego_desc">
       			<h2>Описание</h2>
				<div itemprop="description">
					<?=Helpers::phReplace($data['content'])?>
				</div>
            	
			</div>
    	</div>
    	<?}?>
    </div>
</div>

<div style="margin-bottom: 50px;"></div>

</main>

<?
$script = <<< JS

/*
$('input.cartCount').focus(function(){
    $(this).blur();
})
*/

/* Calc price */



JS;
$this->registerJs($script, yii\web\View::POS_READY);