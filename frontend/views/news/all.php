<?

$this->registerCss('
.news-div .inner-block{
	padding: 10px;
    border: 1px solid #f3f1f1;
    border-top: none;
}
.dateLabel{
	position: absolute;
    margin-top: -44px;
    background: #fff;
    padding: 0px 3px;
}
');
?>

<div class="container offers-page mt-5">
    <div class="row">
        <div class="col-12"><h1 class="title">Новости компании</h1></div>

			
			<?foreach($items as $d){?>
			<div class="col-12 col-md-4 news-div">
				<a href="/news/<?=$d['id']?>/">
			    	<img data-src="<?=$d['image']?>" src="<?=$d['image']?>" class="lazy loaded" data-was-processed="true">
			        <div class="inner-block">
			        	<span class="dateLabel"><?=date_create_from_format('Y-m-d H:i:s',$d['dateCreated'])->format('d.m.Y')?></span>
			            <h6><?=$d['name']?></h6>
			        </div>
		        </a>
		    </div>
		    <?}?>
		    
		    <?if(!$items){?>
		    <div class="col-12">
		    	<p class="text-center">Здесь скоро что-то будет.</p>
		    </div>
		    <?}?>
		
    </div>
</div>