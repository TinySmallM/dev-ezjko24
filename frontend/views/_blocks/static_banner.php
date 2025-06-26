<?

$this->registerCss('
	.static__banner{
    	height: 106px;
	}
	.static__banner_h1{
		text-transform: uppercase;
	    font-weight: 600;
	    font-size: 1.8rem;
	    margin-bottom: 20px;
	    color: #222;
	    padding-top: 115px;
	}

	@media (max-width: 429px) {
		.static__banner_h1{
			font-size: 1.4rem;
		}
	}
	
');

?>

<div class="container">
<?if($h1){?>
	<h1 class="static__banner_h1"><?=$h1?></h1>
<?}?>

<?if($h2){?>
	<h2 class="static__banner_h1"><?=$h2?></h2>
<?}?>

<?if(isset($text)){?>
	<div class="page_text">
		<?=$text?>
	</div>
<?}?>

</div>