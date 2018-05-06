<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-block">
	<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => ['class' => 'form-layout', 'role' => 'form']]); ?>
		<div class="text-center m-b">
			<h4 class="text-uppercase">Reset Password</h4>
		</div>
		<div class="form-inputs">
			<label class="text-uppercase">Your New Password</label>
			<?= $form->field($model, 'password',['inputOptions' => ['class'=>'form-control input-lg','placeholder' => 'Your New Password']])->passwordInput()->label(false); ?>
		</div>
		<?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-lg m-b', 'name' => 'login-button']) ?>
        <?php ActiveForm::end(); ?>
</div>
