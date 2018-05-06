<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-block">
	<?php $form = ActiveForm::begin(['id' => 'login-form','options' => ['role' => 'form', 'class' => 'form-layout']]); ?>
		<div class="text-center m-b">
			<!--<img src="<?php /*// Url::to('@web/images/', true);*/?>">-->
			<h4 class="text-uppercase">Welcome</h4>

		</div>
		<div class="form-inputs">
			<label class="text-uppercase">Username</label>
			<?= $form->field($model, 'username',['inputOptions' => ['class'=>'form-control input-lg','placeholder' => 'Username']])->label(false);?>
			<label class="text-uppercase">Password</label>
			<?= $form->field($model, 'password',['inputOptions' => ['class'=>'form-control input-lg','placeholder' => 'Password']])->passwordInput()->label(false); ?>
		</div>
		<?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-lg m-b', 'name' => 'login-button']) ?>
		<p class="text-center">
			<small>
				<em>By clicking Log in you agree to our <a href="#">terms and conditions</a></em>
			</small>
		</p>
	<?php ActiveForm::end(); ?>
</div>
<a class="bottom-link" href="<?= Url::toRoute(['site/request-password-reset']);?>">Forgot password?</a>
<!--ui-sref="user.forgot" -->