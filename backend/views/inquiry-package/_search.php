<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InquiryPackageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inquiry-package-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inquiry_id') ?>

    <?= $form->field($model, 'package_id') ?>

    <?= $form->field($model, 'date_of_travel') ?>

    <?= $form->field($model, 'length_of_stay') ?>

    <?php // echo $form->field($model, 'guest_count') ?>

    <?php // echo $form->field($model, 'rooms') ?>

    <?php // echo $form->field($model, 'check_in') ?>

    <?php // echo $form->field($model, 'check_out') ?>

    <?php // echo $form->field($model, 'itinerary') ?>

    <?php // echo $form->field($model, 'pricing') ?>

    <?php // echo $form->field($model, 'terms_and_conditions') ?>

    <?php // echo $form->field($model, 'other_info') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
