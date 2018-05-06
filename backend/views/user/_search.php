<?php

use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;
use backend\models\enums\UserTypes;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InquirySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>
<div class="card-block">
    <div class="row">
        <a id="followsearchbtn" class="btn btn-icon-icon btn-danger pull-right"><i class="fa fa-search"></i></a>
        &nbsp;&nbsp; &nbsp;&nbsp;
        <?= Html::submitButton('<i class="fa fa-file-excel-o fa-2x"></i>', ['class' => 'btn btn-icon-icon btn-success pull-right', 'name' => 'export']) ?>
    </div>
</div>

<div id="followupsearch">
    <div class="card bg-white">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Search</strong>
        </div>
        <div class="card-block">

            <div class="row">
                <div class="col-md-3 ">
                    <?= $form->field($model, 'username')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Name'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'email')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Email'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'role')->dropDownList(UserTypes::$headers,['prompt'=> 'Select Role'])->label(false); ?>
                </div>
                <div class="col-sm-3">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary ','name' => 'search']) ?>
                </div>
            </div>

        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>

<br/>
<?php
$this->registerJs('
$(document).ready(function(){
$("#followupsearch").hide();

$("#followsearchbtn").click(function(){
$("#followupsearch").slideToggle("hide");

});


$(".resetsearch").val("");
$("#custom-reset").click(function(){

$("select option:selected").removeAttr("selected");
//$(".resetsearch").val("");
$("#usersearch-username").val("");
$("#usersearch-email").val("");
});

});


');
?>
