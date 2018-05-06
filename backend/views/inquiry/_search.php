<?php

use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;
use common\models\Agent;
use common\models\Inquiry;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InquirySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['index','type' => InquiryStatusTypes::QUOTED],
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
                <div class="col-md-3">
                    <?= $form->field($model, 'pn')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Name'])->label(false); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'quoted_email')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Email'])->label(false); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'pess_mobile')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Mobile'])->label(false); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'quoted_nights')->dropDownList(range(0,15),['class'=>'form-control resetsearch','prompt' => 'Select No Of Nights'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 margin-bottom-5">
                    <?php // $form->field($model, 'pess_date')->textInput(['placeholder' => 'From Date'])->label(false); ?>
                    <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                        <?php if (isset(Yii::$app->request->get('InquirySearch')['pess_date']))
                                $s_date = Yii::$app->request->get('InquirySearch')['pess_date'];
                              else
                                $s_date ='';?>
                        <input type="text" name="InquirySearch[pess_date]" class="form-control resetsearch" id="inquirysearchingfrom"
                               data-provide="datepicker" data-date-format="M-dd-yyyy"  placeholder="Select From Date"
                               value="">
                    </div>
                </div>

                <div class="col-md-3 margin-bottom-5">
                    <?php //  $form->field($model, 'quoted_returndate')->textInput(['placeholder' => 'Return Date'])->label(false); ?>
                    <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                        <?php if (isset(Yii::$app->request->get('InquirySearch')['quoted_returndate']))
                                $r_date = Yii::$app->request->get('InquirySearch')['quoted_returndate'];
                              else
                                $r_date = '';?>
                        <input type="text" name="InquirySearch[quoted_returndate]" class="form-control resetsearch" id="inquirysearchingreturn"
                               data-provide="datepicker" data-date-format="M-dd-yyyy"  placeholder="Select Return Date"
                               value="">
                    </div>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'dest')->textInput(['placeholder' => 'Destination', 'class' => 'resetsearch form-control'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'quoted_leaving_from')->textInput(['placeholder' => 'Leaving From', 'class' => 'resetsearch form-control'])->label(false); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 ">
                    <?= $form->field($model, 'quoted_customer_type')->dropDownList(['1'=>'Customer','2'=>'Agent'],['prompt'=> 'Select Customer Type'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'quoted_inquiry_type')->dropDownList(InquiryTypes::$headers,['prompt' => 'Select Inquiry Type '])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'quoted_source')->dropDownList(SourceTypes::$headers,['prompt' => 'Select Source Type'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'quoted_inquiry_priority')->dropDownList(InquiryPriorityTypes::$headers,['prompt' => 'Select priority'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <?=Html::hiddenInput('',$s_date,['id' => 'hidden-start','class' => 'resetsearch'])?>
                <?=Html::hiddenInput('',$r_date,['id' => 'hidden-end','class' => 'resetsearch'])?>
            </div>
            <?php
            $qm =  User::getQuotationManager();
            reset($qm);

            $first_key = key($qm);

            $fm =  User::getFollowupManager();
            reset($fm);
            $fk = key($fm);
            //echo $first_key; exit;

            ?>
            <div class="row margin-bottom-5">
                <div class="col-md-2 ">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'qu_head',
                        //'name' => 'InquirySearch[qu_head]',
                        'id' => 'qu_head',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Inquiry Head'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>

                </div>
                <div class="col-md-3 margin-bottom-5">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'qu_manager',
                        //'name' => 'InquirySearch[qu_manager]',
                        'id' => 'qu_manager',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Quotation Manager'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
                <div class="col-md-2 margin-bottom-5">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'qu_staff',
                        //'name' => 'InquirySearch[qu_staff]',
                        'id' => 'qu_staff',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Quotation Staff'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
                <div class="col-md-3 margin-bottom-5">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'fu_manager',
                        // 'name' => 'InquirySearch[fu_manager]',
                        'id' => 'fu_manager',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Followup Manager'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
                <div class="col-md-2 margin-bottom-5">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'fu_staff',
                        //'name' => 'InquirySearch[fu_staff]',
                        'id' => 'fu_staff',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Followup Staff'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>


                </div>
            </div>

            <div class="row margin-bottom-5">
                <div class="col-sm-2" style="margin-top: 10px;">
                    <select class="form-control select" name="InquirySearch[followup_type]" id="followup_type">
                        <?php foreach($followup_type as $k=>$v):
                            echo '<option ' . ( ($f_type==$k)?"selected":"" ) .' value='.$k .'>'.$v.'</option>';
                        endforeach;?>
                    </select>
                </div>
                <div class="col-sm-2" style="margin-top: 10px;">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'qu_agent',
                        //'name' => 'InquirySearch[qu_staff]',
                        'id' => 'qu_agent',
                        'value' => '', // initial value
                        'data' => Agent::getAgent(),
                        'options' => ['placeholder' => 'Select Agent'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
                <div class="col-md-2" style="margin-top :10px;">

                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' => 'quoted_inquiry_id',

                        'value' => '', // initial value
                        'data' => Inquiry::getInquiryId(),
                        'addon' => [
                            'prepend' => [
                                'content' => 'KR-'
                            ],],
                        'options' => ['placeholder' => 'Select Inquiry-id'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
                <div class="col-md-2 margin-bottom-5">
                    <?= $form->field($model, 'highly_interested')->dropDownList(Inquiry::$headers,['prompt' => 'Select Interest'])->label(false); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 pull-right">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary ','name' => 'search']) ?>
                    <?= Html::button('Reset', ['class' => 'btn btn-primary','id'=>'custom-reset']) ?>
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
  var s_dt = $("#hidden-start").val();
    var e_dt = $("#hidden-end").val();
    $("#inquirysearchingfrom").val(s_dt);
    $("#inquirysearchingreturn").val(e_dt);

    $("#inquirysearchingfrom").change(function(){
			var st_date =$("#inquirysearchingfrom").val();
			var en_date =$("#inquirysearchingreturn").val();
            if (st_date > en_date) {
                $("#inquirysearchingreturn").datepicker("setDate",st_date);
            }
            $("#inquirysearchingreturn").datepicker("setStartDate",st_date);
		});
$("#custom-reset").click(function(){

var reset_val="";
$(".resetsearch").val("");

$("select option:selected").removeAttr("selected");

$("#inquirysearch-qu_staff").select2("val","");
$("#inquirysearch-fu_staff").select2("val","");
$("#inquirysearch-qu_manager").select2("val", "");
$("#inquirysearch-fu_manager").select2("val", "");
$("#inquirysearch-qu_head").select2("val", "");
$("#inquirysearch-qu_agent").select2("val", "");
$("#inquirysearch-quoted_inquiry_id").select2("val", "");
  var s_dt = $("#hidden-start").val();
    var e_dt = $("#hidden-end").val();
    $("#inquirysearchingfrom").val(s_dt);
    $("#inquirysearchingreturn").val(e_dt);
});

});


');
?>
