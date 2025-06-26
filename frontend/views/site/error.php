<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
  <?=$this->render('../_blocks/nt_header')?>

		<div class="cover" style="margin-bottom: 40px;">
		
			<div class="site-error">

				<h1><?= Html::encode($this->title) ?></h1>
			
				<div class="alert alert-danger">
					<?= nl2br(Html::encode($message)) ?>
				</div>
			
				<p>Перемещение на главную через <span class="timer">5</span> сек.</p>

				<script>
					
					setTimeout(function(){
						window.location.href="/"
					}, 5300)
					
					let num_sec = 5;
					setInterval(function(){
					$('.timer').html(num_sec);
					num_sec--;
					}, 1000)
				</script>
			
			</div>
		
		</div>
	</div>
</div>
