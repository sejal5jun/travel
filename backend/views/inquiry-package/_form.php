<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InquiryPackage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inquiry-package-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inquiry_id')->textInput() ?>

    <?= $form->field($model, 'package_id')->textInput() ?>

    <?= $form->field($model, 'date_of_travel')->textInput() ?>

    <?= $form->field($model, 'length_of_stay')->textInput() ?>

    <?= $form->field($model, 'guest_count')->textInput() ?>

    <?= $form->field($model, 'rooms')->textInput() ?>

    <?= $form->field($model, 'check_in')->textInput() ?>

    <?= $form->field($model, 'check_out')->textInput() ?>

    <?= $form->field($model, 'itinerary')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pricing')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'terms_and_conditions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'other_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
