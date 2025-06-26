<?php
	use yii\helpers\Html;

	use backend\assets\AppAssetFrame;
	AppAssetFrame::register($this);
?>
<?$this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
	<head>
		<title><?= Html::encode($this->title) ?></title>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php $this->head() ?>
	</head>
	<body>
		<?$this->beginBody()?>
		<?=$content?>
		<?$this->endBody()?>
	</body>
</html>
<?$this->endPage()?>