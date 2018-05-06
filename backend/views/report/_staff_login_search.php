<?php

use backend\models\enums\InquiryActivityTypes;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RecordLoginSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['staff-login'],
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
                        <label class="control-label">User</label>
                        <?= Select2::widget([
                            'model' => $model,
                            'attribute' => 'user_id',
                            'value' => $user, // initial value
                            'data' => User::getAllUsers(),
                            'options' => ['placeholder' => 'Select User'],
                            'pluginOptions' => [
                                'tags' => false,
                                'tokenSeparators' => [',', ' '],
                                'maximumInputLength' => 10,
                            ],
                        ]); ?>
                    </div>
                   <div class="col-sm-3">
                        <label class="control-label">Start Date</label>
                        <?= Html::textInput('RecordLoginSearch[start_date]', '', ['id' => 'start_date', 'class' => 'form-control resetsearch', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy']) ?>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">End Date</label>
                        <?= Html::textInput('RecordLoginSearch[end_date]', '', ['id' => 'end_date', 'class' => 'form-control resetsearch', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy']) ?>
                    </div>
                    <?= Html::hiddenInput('', $s_date, ['id' => 'hidden-start', 'class' => 'resetsearch']) ?>
                    <?= Html::hiddenInput('', $e_date, ['id' => 'hidden-end', 'class' => 'resetsearch']) ?>
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
    //$("#report-search").hide();

    var s_dt = $("#hidden-start").val();
    var e_dt = $("#hidden-end").val();
    $("#start_date").val(s_dt);
    $("#end_date").val(e_dt);


    $("#custom-reset").click(function(){
        $(".resetsearch").val("");
        $("select option:selected").removeAttr("selected");
          var s_dt = $("#hidden-start").val();
          var e_dt = $("#hidden-end").val();
          $("#start_date").val(s_dt);
          $("#end_date").val(e_dt);
    });

      $("#start_date").change(function(){
			var st_date =$("#start_date").val();
			var en_date =$("#end_date").val();
            if (st_date > en_date) {
                $("#end_date").datepicker("setDate",st_date);
            }
            $("#end_date").datepicker("setStartDate",st_date);
		});
 });
');
?>