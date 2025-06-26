<div class="container offers-page">
    <div class="row">
        <div class="col-12"><h1 class="title"><?=$item['name']?></h1></div>

			

			
				<?if($item['image']){?>
				<div class="col-12 col-md-6">
		    		<img data-src="<?=$item['image']?>" src="<?=$item['image']?>" class="lazy loaded" data-was-processed="true">
		    	</div>
		    	<?}?>
		    
		    <div class="col-12 col-md-6">
		    	<?=$item['content']?>
		    </div>
		
    </div>
</div>

<?=$this->render('../_blocks/form')?>
<?=$this->render('../_blocks/ourteam')?>
<?=$this->render('../_blocks/discount')?>
<?=$this->render('../_blocks/partners')?>
<?=$this->render('../_modals/review')?>