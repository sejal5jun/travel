<?php

use backend\models\enums\InquiryActivityTypes;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RecordBookingSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['booking-report'],
    'method' => 'get',
]);?>
    <div id="report-search">

        <div class="row">
            <div class="col-sm-3 col-sm-offset-9">
                <?= Html::submitButton('<i class="fa fa-file-excel-o fa-2x"></i>', ['class' => 'btn btn-icon-icon btn-success pull-right', 'name' => 'export']) ?>
            </div>
        </div>
        <div class="card bg-white">

            <div class="card-header bg-danger-dark">
                <strong class="text-white">Search</strong>
            </div>
            <div class="card-block">

                <div class="row">
                    <div class="col-sm-3">
                        <label class="control-label">Staff</label>
                        <?= Select2::widget([
                            'model' => $model,
                            'attribute' => 'user_id',
                            'value' => $user, // initial value
                            'data' => User::getBookingStaff(),
                            'options' => ['placeholder' => 'Select Staff'],
                            'pluginOptions' => [
                                'tags' => false,
                                'tokenSeparators' => [',', ' '],
                                'maximumInputLength' => 10,
                            ],
                        ]); ?>
                    </div>
                   <div class="col-sm-3">
                        <label class="control-label">Year</label>
                        <?= Html::textInput('RecordBookingSearch[year]',date('Y'), ['id' => 'year', 'class' => 'form-control' ]) ?>
                    </div>
                    <?= Html::hiddenInput('', $year, ['id' => 'hidden-year', 'class' => 'resetsearch']) ?>
                </div>
                <div class="row">
                    <div class="col-sm-2 pull-right">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search']) ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs('
$(document).ready(function(){
    $("#year").datepicker( {
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years",
    });
    var year = $("#hidden-year").val();
    $("#year").val(year);

    $("#custom-reset").click(function(){
        $(".resetsearch").val("");
        $("select option:selected").removeAttr("selected");
          var year = $("#hidden-year").val();
          $("#year").val(year);
        });
 });
');
?>