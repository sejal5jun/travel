<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RoomType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card bg-white">

    <div class="card-header bg-danger-dark">
        <strong class="text-white">Room Type Details</strong>
    </div>

    <div class="card-block">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group row">
            <div class="col-sm-4">
                <?= $form->field($model, 'type')->textInput() ?>
            </div>
        </div>

        <div class="form-group row">
            <?= Html::submitButton($model->isNewRecord ? 'Add New Room Type' : 'Update Room Type', ['class' => 'btn btn-primary btn-round pull-right btn-lg']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
