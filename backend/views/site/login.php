<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->registerCss('
body{
	background-color: #292828;
}
');
?>
<?/*
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->label('Логин')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->label('Пароль')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->label('Запомнить меня')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
*/?>

<div class="container">
	<div class="row justify-content-center align-items-center" style="height:100vh">
		<div class="col-12 col-md-6 col-xl-5">
			<div class="card">
				<h4 class="card-title text-center mt-3" style="font-size: 20px;padding: 5px;">Авторизация</h4>
				<div class="card-body">
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
						<div class="form-group">
							<?= $form->field($model, 'username')->label('Логин')->textInput(['autofocus' => true]) ?>
						</div>
						<div class="form-group">
							<?= $form->field($model, 'password')->label('Пароль')->passwordInput() ?>
						</div>
						<?= $form->field($model, 'rememberMe')->label('Запомнить меня')->checkbox() ?>
						<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>