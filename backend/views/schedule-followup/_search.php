<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleFollowupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-followup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inquiry_id') ?>

    <?= $form->field($model, 'passenger_email') ?>

    <?= $form->field($model, 'text_body') ?>

    <?= $form->field($model, 'scheduled_at') ?>

    <?php // echo $form->field($model, 'scheduled_by') ?>

    <?php // echo $form->field($model, 'is_sent') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
