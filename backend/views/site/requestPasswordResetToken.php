<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-block">
	<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => ['class' => 'form-layout', 'role' => 'form']]); ?>
		<div class="text-center m-b">
			<h4 class="text-uppercase">Reset Password</h4>
		</div>
		<div class="form-inputs">
			<label class="text-uppercase">Your email address</label>
			<?= $form->field($model, 'email',['inputOptions' => ['class' => 'form-control input-lg', 'placeholder' => 'Email address', 'autofocus' => 'true']])->label(false); ?>
		</div>
		<?= Html::submitButton('Reset Password', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
	<?php ActiveForm::end(); ?>
</div>
<a href="<?= Url::toRoute(['site/login']);?>" class="bottom-link">Login instead.</a>