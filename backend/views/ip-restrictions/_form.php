<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\IpRestrictions */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="card bg-white">
        <div class="card-header bg-danger-dark">
            <strong class="text-white">Ip Details</strong>
        </div>
	   	<div class="card-block row">
	        <div class="col-sm-4">
	        	<?= $form->field($model, 'ip')->textInput(['maxlength' => true])->hint('You can enter comma separated ips. e.g. 127.0.0.1,198.0.0.1') ?>
            	<?= Html::submitButton($model->isNewRecord ? 'Add New IP Restriction' : 'Update IP', ['class' => 'btn btn-primary btn-round', 'id' => 'add-ip']) ?>
	        </div>
	   	</div>
   	</div>
<?php ActiveForm::end(); ?>
