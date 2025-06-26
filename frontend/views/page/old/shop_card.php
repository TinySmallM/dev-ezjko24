<?

use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->title = Helpers::phReplace($data['title']);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$inOne = 1;
$priceOne = $data['price1_sum'];
$priceNameOne = $data['price1_name'];
if($data['price1_sum'] && $data['price2_sum']){
	if($data['price1_sum'] != $data['price2_sum']){
		$inOne = round( $data['price1_sum']/$data['price2_sum'] );
		$priceOne = $data['price2_sum'];
		$priceNameOne = $data['price2_name'];
	}
}
else if( $data['price2_mincount'] ){
	$inOne = $data['price2_mincount'];
}

?>
<!-- Product block -->
<div class="container product-page">
    <div class="row">
    	<!--
        <div class="col-12">
            <ol class="breadcrumb"><li class="breadcrumb-item"><a href="/">Главная страница</a></li>
				<li class="breadcrumb-item"><a href="/aromatizatory/">Ароматизаторы</a></li>
				<li class="breadcrumb-item active">Ароматизатор Румбарум (ромовый) 3кг</li>
			</ol>
        </div>
        -->
       
       <!-- h1 -->
        <div class="col-12">
            <h1 class="title text-left title-card"><?=Helpers::phReplace($data['h1'])?></h1>
        </div>
        
        <!-- Image -->
		<div class="col-12 col-md-5 mb-5 mb-md-0 offset-md-1" style="border: 1px solid #f7f7f7;">
			<div class="fotorama" data-nav="thumbs" data-allowfullscreen="true" data-fit="contain">
				<?foreach($data['gallery'] as $d){?>
					<a href="/upload/<?=$d['image']?>" data-fit="cover"><img src="/upload/thumb_<?=$d['image']?>" alt="<?=$d['description']?>" alt="<?=$d['description']?>"></a>
				<?}?>
			</div>
        </div>
        
        <!-- Price -->
        <div class="col-12 col-md-5" style="background: #f7f7f7;">
            <h6 class="mb-0 mt-md-4">Цена: </h6>
        	
        	<?if($data['price1_sum'] && $data['price2_sum'] && $data['price1_sum'] != $data['price2_sum']){?>
	            <span class="product-price-field">
	            	<?=$data['price2_sum']?> руб. за 1 <?=$data['price2_name']?>
	            </span>
	            
	            <div class="small-price">
	            	 <span class="product-price-field2">
	            	 	<span id="tovar_price2"><?=$data['price1_sum']?></span> руб. за 1 <?=$data['price1_name']?>
	            	 </span>
	            </div>
            <?} else {?>
            	<span class="product-price-field">
	            	<?=$priceOne?> руб. за 1 <?=$priceNameOne?>
	            </span>
            <?}?>
            
            <h6 style="margin-top:30px;">Количество: </h6>
    		<form class="cartAdd">
    			<div class="form-group">
    				<input type="number" step="<?=$inOne?>" value="<?=$inOne?>" min="<?=$inOne?>" max="1000" data-priceone="<?=$priceOne?>" data-inone="<?=$inOne?>" class="cartCount">
    				<span class="dec-div-6"><?=$priceNameOne?>, <span class="sumCalc"><?=$priceOne*$inOne?></span> руб</span>
    				<input type="hidden" name="count" value="1">
        			<input type="hidden" name="id" value="<?=$data['id']?>">
    			</div>
    			<div class="form-group">
    				<button class="btn btn-primary" type="submit">Добавить в корзину</button>
    			</div>
			</form>        	
        </div>
        
        <!-- About -->
        <?if($data['content']){?>
    	<div class="col-md-12 mt-5">
       		<h6>О продукте:</h6>
            <?=Helpers::phReplace($data['content'])?>
    	</div>
    	<?}?>

		<!-- Price calc -->
		<?if($data['chunk']['services1']['content']){?>
		<table class="table table-hover mt-4">
			<thead>
				<tr>
					<th colspan="2">Услуга</th>
					<th>Цена</th>
				</tr>
			</thead>
			<tbody>
				<?foreach($data['chunk']['services1']['content'] as $i=>$d){?>
				<tr>
					<td style="width:15%">
						<a data-fancybox="gallery" href="/upload/<?=$d['image']?>">
							<img src="/upload/thumb_<?=$d['image']?>" class="img">
						</a>
					</td>
					<td style="width:75%">
						<div style="font-weight: 600"><?=$d['name']?></div>
						<div><?=$d['description']?></div>
					</td>
					<td style="width:10%"><?=$d['price']?> РУБ</td>
				</tr>
				<?}?>
			</tbody>
		</table>
		<?}?>
    </div>
</div>

<?=$this->render('../_blocks/form')?>
<?=$this->render('../_blocks/ourteam')?>
<?=$this->render('../_blocks/discount')?>
<?=$this->render('../_blocks/partners')?>

<?
$script = <<< JS

$('input.cartCount').focus(function(){
    $(this).blur();
})

/* Calc price */
$('input.cartCount').change(function(){
    let in_one = $(this).data('inone');
    let price_one = $(this).data('priceone');
    let count_now = $(this).val();
    
    
    $(this).siblings('input[name="count"]').val(count_now/in_one);
    $('span.sumCalc').text(count_now*price_one);
})

$('form.cartAdd').submit(function(){
	
	let form = $(this);
	let data = form.serializeFormJSON();
	
	axios.post('/cart/api/?type=update', data)
	.then(function (res) {
		res = res.data;
	    console.log(res);
	    if(res.success == true){
			saToast.fire({
			  icon: 'success',
			  title: 'Добавлено в корзину'
			})
	    }
	    else {
	    	saToast.fire({
			  icon: 'error',
			  title: 'Что-то не так'
			})
	    }
	    
	})
	
	
	return false;
	
})


JS;
$this->registerJs($script, yii\web\View::POS_READY);