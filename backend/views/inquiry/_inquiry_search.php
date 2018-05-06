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
    'action' => ['index','type' => $type],
    'method' => 'get',
]);?>
    <div class="card-block margin-less">
        <div class="row ">
            <a id="inquirysearchbtn" class="btn btn-icon-icon btn-danger pull-right"><i class="fa fa-search"></i></a>
            &nbsp;&nbsp; &nbsp;&nbsp;
            <?= Html::submitButton('<i class="fa fa-file-excel-o fa-2x"></i>', ['class' => 'btn btn-icon-icon btn-success pull-right', 'name' => 'export']) ?>
        </div>
    </div>

    <div id="inquirysearch">
        <div class="card bg-white">

            <div class="card-header bg-danger-dark">
                <strong class="text-white">Search</strong>
            </div>
            <div class="card-block">

                <div class="row">
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'name')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Name'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'email')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Email'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'mobile')->textInput(['class'=>'form-control resetsearch','placeholder' => 'Mobile'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'no_of_days')->dropDownList(range(0,15),['class'=>'form-control resetsearch','prompt' => 'Select No Of Nights'])->label(false); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 margin-bottom-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <?php if (isset(Yii::$app->request->get('InquirySearch')['from_date']))
                                    $s_date = Yii::$app->request->get('InquirySearch')['from_date'];
                                  else
                                    $s_date=''?>
                            <input type="text" name="InquirySearch[from_date]" class="form-control resetsearch" id="inquirysearchingfrom"
                                   placeholder="Select From Date" data-provide="datepicker" data-date-format="M-dd-yyyy"
                                   value="">
                        </div>
                </div>

                <div class="col-md-3 margin-bottom-5">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <?php if (isset(Yii::$app->request->get('InquirySearch')['return_date']))
                                $r_date = Yii::$app->request->get('InquirySearch')['return_date'];
                               else
                                   $r_date=''?>
                        <input type="text" name="InquirySearch[return_date]" class="form-control resetsearch" id="inquirysearchingreturn"
                              placeholder="Select Return Date" data-provide="datepicker" data-date-format="M-dd-yyyy"
                               value="">
                    </div>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'destination')->textInput(['placeholder' => 'Destination'])->label(false); ?>
                </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'leaving_from')->textInput(['placeholder' => 'Leaving From'])->label(false); ?>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'customer_type')->dropDownList(['1'=>'Customer','2'=>'Agent'],['prompt'=> 'Select Customer Type'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'type')->dropDownList(InquiryTypes::$headers,['prompt' => 'Select Inquiry Type'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'source')->dropDownList(SourceTypes::$headers,['prompt' => 'Select Source Type'])->label(false); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?= $form->field($model, 'inquiry_priority')->dropDownList(InquiryPriorityTypes::$headers,['prompt' => 'Select priority','options' => [ $priority => ['Selected' => 'selected']]])->label(false); ?>
                    </div>
                </div>

                <?php
                $qm =  User::getQuotationManager();
                reset($qm);

                $first_key = key($qm);

                $fm =  User::getFollowupManager();
                reset($fm);
                $fk = key($fm);
               //echo $fk; exit;?>
                <div class="row margin-bottom-5">

                    <div class="col-md-2 margin-bottom-5">
                        <?php // $form->field($model, 'qu_head')->textInput(['placeholder' => 'Inquiry Head '])->label(false); ?>
                        <?= Select2::widget([
                            'model'=> $model,
                            'attribute' => 'inquiry_head',
                            //'name' => 'InquirySearch[qu_head]',
                            'id' => 'inquiry_head',
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
                            'attribute' => 'quotation_manager',
                            //'name' => 'InquirySearch[qu_manager]',
                            'id' => 'quotation_manager',
                            'class'=>'select2_inquiry',
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
                            'attribute' => 'quotation_staff',
                            //'name' => 'InquirySearch[qu_staff]',
                            'id' => 'quotation_staff',
                            'class'=>'select2_inquiry',
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
                        'attribute' => 'follow_up_head',
                        // 'name' => 'InquirySearch[fu_manager]',
                        'id' => 'follow_up_head',
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
                        'attribute' => 'follow_up_staff',
                        //'name' => 'InquirySearch[fu_staff]',
                        'id' => 'follow_up_staff',
                        'value' => '', // initial value
                        'data' => User::getHead(),
                        'options' => ['placeholder' => 'Select Followup Staff'],
                        'pluginOptions' => ['allowClear'=>'true','tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
            </div>
                <div class="row">
                    <?php if($type == InquiryStatusTypes::COMPLETED){?>
                    <div class="col-md-2 margin-bottom-5">
                        <?= Select2::widget([
                            'model'=> $model,
                            'attribute' => 'booking_staff',
                            //'name' => 'InquirySearch[fu_staff]',
                            'id' => 'booking_staff',
                            'value' => '', // initial value
                            'data' => User::getHead(),
                            'options' => ['placeholder' => 'Select Booking Staff'],
                            'pluginOptions' => ['allowClear'=>'true','tags' => false,
                                'tokenSeparators' => [',', ' '],
                                'maximumInputLength' => 10],
                        ]); ?>
                    </div>
                    <?php }?>
                    <div class="col-md-2 margin-bottom-5">
                        <?= Select2::widget([
                            'model'=> $model,
                            'attribute' => 'agent_id',
                            //'name' => 'InquirySearch[qu_staff]',
                            'id' => 'agent_id',
                            'class'=>'select2_inquiry',
                            'value' => '', // initial value
                            'data' => Agent::getAgent(),
                            'options' => ['placeholder' => 'Select Agent'],
                            'pluginOptions' => ['allowClear'=>'true','tags' => false,
                                'tokenSeparators' => [',', ' '],
                                'maximumInputLength' => 10],
                        ]); ?>
                    </div>
                    <div class="col-md-2 margin-bottom-5">

                        <?= Select2::widget([
                            'model'=> $model,
                            'attribute' => 'inquiry_id',

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
                    <?=Html::hiddenInput('hidden_start',$s_date,['id' => 'hidden-start','class' => 'resetsearch'])?>
                    <?=Html::hiddenInput('hidden_end',$r_date,['id' => 'hidden-end','class' => 'resetsearch'])?>
                </div>

	            <div class="row">
                    <div class="col-sm-2 pull-right">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search']) ?>
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
$("#inquirysearch").hide();

$("#inquirysearchbtn").click(function(){
$("#inquirysearch").slideToggle("hide");

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

$(".resetsearch").val("");
$("#inquirysearch-email").val(" ");
$("#inquirysearch-mobile").val("");
$("#inquirysearch-destination").val("");
$("#inquirysearch-leaving_from").val("");
$("#inquirysearch-no_of_days").val("");

var reset_val = "";
$(".resetsearch").val(reset_val);


$("select option:selected").removeAttr("selected");


$("#inquirysearch-quotation_staff").select2("val","");
$("#inquirysearch-quotation_manager").select2("val","");

$("#inquirysearch-follow_up_staff").select2("val", "");
$("#inquirysearch-follow_up_head").select2("val", "");
$("#inquirysearch-inquiry_head").select2("val", "");
$("#inquirysearch-booking_staff").select2("val", "");
$("#inquirysearch-agent_id").select2("val", "");
$("#inquirysearch-inquiry_id").select2("val", "");
 var s_dt = $("#hidden-start").val();
    var e_dt = $("#hidden-end").val();
    $("#inquirysearchingfrom").val(s_dt);
    $("#inquirysearchingreturn").val(e_dt);
});

});


');
?>
