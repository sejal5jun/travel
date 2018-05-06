<?php

use backend\models\enums\CustomerTypes;
use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;
use common\models\RoomType;
use common\models\Agent;
use common\models\City;
use common\models\User;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Inquiry */
/* @var $form yii\widgets\ActiveForm */
/* @var $age_model common\models\InquiryChildAge */
/* @var $agent_model common\models\Agent */
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="card bg-white">
        <div class="card-header bg-danger-dark">
            <strong class="text-white">Inquiry Details</strong>
        </div>

        <div class="card-block">
            <h4>Passenger Details:</h4>
            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'name')->textInput() ?>
                </div>

                <div class="col-sm-3">
                    <?= $form->field($model, 'email')->textInput() ?>
                </div>

                <div class="col-sm-3">
                    <?= $form->field($model, 'mobile')->textInput() ?>
                </div>
            </div>

            <h4>Travelling Details:</h4>
            <?php if ($model->isNewRecord) { ?>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <?= $form->field($model, 'from_date')->textInput(['id' => 'from_date', 'class' => 'form-control', 'name' => 'Inquiry[from_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    </div>

                    <div class="col-sm-3">
                        <label class="control-label">No Of Nights</label>
                        <?= $form->field($model, 'no_of_days')->dropDownList(range(0,25))->label(false) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'return_date')->textInput(['id' => 'return_date', 'class' => 'form-control', 'name' => 'Inquiry[return_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    </div>


                </div>
            <?php } else { ?>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <?= $form->field($model, 'from_date')->textInput(['id' => 'from_date', 'class' => 'form-control', 'name' => 'Inquiry[from_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d', 'value' => date("M-d-Y", strtotime($model->from_date))]); ?>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">No Of Nights</label>
                        <?= $form->field($model, 'no_of_days')->dropDownList(range(0,25))->label(false) ?>
                    </div>

                    <div class="col-sm-3">
                        <?= $form->field($model, 'return_date')->textInput(['id' => 'return_date', 'class' => 'form-control', 'name' => 'Inquiry[return_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d', 'value' => date("M-d-Y", strtotime($model->return_date))]); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'destination')->textInput() ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'leaving_from')->textInput(['value'=>'Mumbai']) ?>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Room Count</label>
                    <?= $form->field($model, 'room_count')->dropDownList(range(0,12),['options'=>[ 1 =>['Selected'=>'selected']]])->label(false) ?>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Room Type</label>
                    <?= Select2::widget([
                        'name' => 'room_type',
                        'id' => 'room',
                        //'value' => $room_arr, // initial value
                        'value' => 'double bed', // initial value
                        'data' => RoomType::getRoomTypes(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a RoomType', 'multiple' => true],
                        'pluginOptions' => ['tags' => true],
                    ]); ?>
                    <p class="theme_hint help-block">&nbsp You can also add a new Room Type</p>

                    <div class='error_room error-hint help-block' style="display:none;">Room Type cannot be a
                        number.
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">
                    <label class="control-label">Adult Count</label>
                    <?= $form->field($model, 'adult_count')->dropDownList(range(0,12),['options'=>[ 2 =>['Selected'=>'selected']]])->label(false) ?>
                </div>

                <div class="col-sm-2 col-sm-offset-1">
                    <label class="control-label">Children Count</label>
                    <?= $form->field($model, 'children_count')->dropDownList(range(0,12))->label(false) ?>
                </div>
                <div class="col-sm-4 col-sm-offset-1">
                    <div id="age" class="age_dropdown text-center">
                        <?php if (count($child_age) > 0) { ?>
                            <?php foreach ($child_age as $val) { ?>
                                <input class="age_child text-center" placeholder="Child 1 Age"
                                       name="InquiryChildAge[age][]" type="text" value=<?= $val ?>>
                            <?php } ?>
                        <?php } else { ?>
                            <input class="age_child text-center" placeholder="Child 1 Age"
                                   name="InquiryChildAge[age][]" type="text"/>
                        <?php } ?>
                    </div>
                    <div id="age_validate" class="error-hint">Age must be an integer</div>
                </div>
            </div>

            <h4>Inquiry Details:</h4>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label">Customer Type</label>
                    <?= $form->field($model, 'customer_type')->dropDownList(CustomerTypes::$headers, ['options' => [$model->customer_type => ['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="agent_div" style="display: none;">
                    <div class="col-sm-3">
                        <label class="control-label">City</label>

                        <div class="form-group">
                            <?= Html::dropDownList('city', $city_name, City::getCityId(), ['class' => 'form-control city']); ?>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <label class="control-label">Agent</label>
                        <?= $form->field($model, 'agent_id')->dropDownList(Agent::getAgent($city_name), ['options' => [$model->agent_id => ['Selected' => 'selected']]])->label(false); ?>
                        <div class="agent_error help-block error-hint" style="display:none;">Agent can not be
                            blank.
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <label class="control-label">Add Agent</label><br/>
                        <a class="fa fa-plus-circle fa-2x" data-toggle="modal" data-target=".bs-modal-sm"></a>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label">Inquiry Type</label>
                    <?= $form->field($model, 'type')->dropDownList(InquiryTypes::$headers, ['options' => [$model->type => ['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="col-sm-3">
                    <label class="control-label">Inquiry Priority</label>
                    <?= $form->field($model, 'inquiry_priority')->dropDownList(InquiryPriorityTypes::$headers, ['options' => [$model->inquiry_priority => ['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="col-sm-3">
                    <label class="control-label">Source</label>
                    <?= $form->field($model, 'source')->dropDownList(SourceTypes::$headers, ['options' => [$model->source => ['Selected' => 'selected']],'class' => 'source_type form-control'])->label(false); ?>
                </div>
                <div class="col-sm-3 ref-div" style="display: none">
                    <label class="control-label">Reference</label>
                    <?= $form->field($model, 'reference')->textInput()->label(false); ?>
                    <div class="ref_error help-block error-hint" style="display:none;">Reference can not be
                        blank.
                    </div>
                </div>
            </div>
            <?php
            $qm =  User::getQuotationManager();
            reset($qm);
            $first_key = key($qm);
            $fm =  User::getFollowupManager();
            reset($fm);
            $fk = key($fm);

            ?>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label">Inquiry Head</label>
                    <?= $form->field($model, 'inquiry_head')->dropDownList(User::getHead(), ['options' => [Yii::$app->user->identity->id =>['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Quotation Manager</label>
                    <?= $form->field($model, 'quotation_manager')->dropDownList(User::getHead(), ['options' => [$model->quotation_manager => ['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Quotation Staff</label>
                    <?= $form->field($model, 'quotation_staff')->dropDownList(User::getHead(), ['options' => [$model->quotation_staff => ['Selected' => 'selected']]])->label(false); ?>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Follow Up Head</label>
                    <?= $form->field($model, 'follow_up_head')->dropDownList(User::getHead(), ['options' => [$model->follow_up_head => ['Selected' => 'selected']]])->label(false); ?>
                </div>

                <div class="col-sm-2">
                    <label class="control-label">Follow Up Staff</label>
                    <?= $form->field($model, 'follow_up_staff')->dropDownList(User::getHead(), ['options' => [$model->follow_up_staff => ['Selected' => 'selected']]])->label(false); ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <!--  = $form->field($model, 'inquiry_details')->widget(CKEditor::className(), [
                          'options' => ['rows' => 6],
                          'preset' => 'full',
                          'clientOptions' => [
                              'filebrowserUploadUrl' => 'site/url',
                          ]
                      ]) -->
                    <?=$form->field($model, 'inquiry_details')->textarea(['class' => 'summernote']) ?>
                </div>
            </div>
            <div class="form-group row">
                <?= Html::submitButton($model->isNewRecord ? 'Add New Inquiry' : 'Update Inquiry', ['class' => 'btn btn-primary btn-lg btn-round pull-right', 'id' => 'add_inquiry']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
    <div class="modal bs-modal-sm agent-modal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Agent</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'action' => Yii::$app->getUrlManager()->createUrl(['agent/create']),
                        'id' => 'agent-form',
                        'enableAjaxValidation' => false
                    ]); ?>
                    <div class="form-group form-material row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <?= $form->field($agent_model, 'name')->textInput() ?>
                                </div>
                                <div class="col-sm-4">
                                    <label class="control-label">City</label>
                                    <?= Select2::widget([
                                        'name' => 'Agent[city_id]',
                                        'id' => 'city',
                                        'value' => '', // initial value
                                        'data' => City::getCity(),
                                        'options' => ['placeholder' => 'Select City'],
                                        'pluginOptions' => ['tags' => true,],
                                    ]); ?>
                                    <p class="theme_hint help-block">&nbsp; You can also add a new City</p>

                                    <div class='error error-hint help-block' style="display:none;">City cannot be a
                                        number.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer no-border">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?= Html::submitButton('Add New Agent', ['class' => 'btn btn-success pull-right', 'id' => 'add_agent']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php

$this->registerJs('
 $(document).ready(function(){
   $(".dropdown-toggle").remove();
    $(".btn-codeview").remove();
    var source = $("#inquiry-source").val();
     if(source=='.SourceTypes::REFERENCE.'){
        $(".ref-div").show();
     }
    else{
        $(".ref-div").hide();
    }
   $("#inquiry-source").change(function(){
		var source = $(this).val();
        if(source=='.SourceTypes::REFERENCE.'){
            $(".ref-div").show();
        }
         else{
            $(".ref-div").hide();
         }
   });
  $(".details").summernote({
            height: 200,
            onImageUpload: function(files) {
            alert(1);
              url = $(this).data("upload");
                sendFile(files[0], url, $(this));
            }
  });
  function sendFile(file, editor, welEditable) {
         data = new FormData();
         data.append("file", file);
         $.ajax({
              data: data,
              type: "GET",
              url: "'. Yii::$app->getUrlManager()->createUrl(['inquiry/save-image']) . '",
              cache: false,
              contentType: false,
              processData: false,
              success: function(objFile) {
                     editor.summernote("insertImage", objFile.folder+objFile.file);
               }
         });
 }

 /*$("#inquiry-quotation_manager").change(function(){
        var head = $(this).val();
         $.ajax({
              data: {head: head},
              type: "GET",
              url: "'. Yii::$app->getUrlManager()->createUrl(['inquiry/search-quotation-staff']) . '",
              dataType: "json",
              success: function(data) {
                    $("#inquiry-quotation_staff").find("option").remove();
                    $.each(data, function(key, value) {
                        if(key!=""){
                            $("#inquiry-quotation_staff").append($("<option></option>").attr("value", key).text(value));
                        }
                    });
               }
         });
 });*/

/* $("#inquiry-follow_up_head").change(function(){
        var head = $(this).val();
         $.ajax({
              data: {head: head},
              type: "GET",
              url: "'. Yii::$app->getUrlManager()->createUrl(['inquiry/search-followup-staff']) . '",
              dataType: "json",
              success: function(data) {
                    $("#inquiry-follow_up_staff").find("option").remove();
                    $.each(data, function(key, value) {
                        if(key!=""){
                            $("#inquiry-follow_up_staff").append($("<option></option>").attr("value", key).text(value));
                        }
                    });
               }
         });
 });*/

 $("#age").hide();
 $("#age_validate").hide();
   var child_count = $("#inquiry-children_count").val();
   if(child_count!=0){
        $("#age").show();
   }
   else
   {
        $("#age").hide();
    }

    $("#from_date").change(function(){
			var st_date =$("#from_date").val();
			var en_date =$("#return_date").val();
           if(st_date=="")
			{
				$("#inquiry-no_of_days").val(0);
			}
			else{
                if (st_date > en_date) {
                    $("#return_date").datepicker("setDate",st_date);
                }
                $("#return_date").datepicker("setStartDate",st_date);
			}
		});

		$("#return_date").change(function(){
			var st_date =$("#from_date").val();
			var en_date =$("#return_date").val();
			if(st_date=="" ||  en_date=="")
			{
				$("#inquiry-no_of_days").val(0);
			}
			else{
                $("#from_date").datepicker("setEndDate",en_date);
                var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
                var firstDate = new Date(st_date);
                var secondDate = new Date(en_date);
                var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
                $("#inquiry-no_of_days").val(diffDays);
			}
		});

		  $("#inquiry-no_of_days").change(function(){
              var st_date =$("#from_date").val();
              var days= $(this).val();
               if(st_date=="")
              {
                  $("#inquiry-no_of_days").val(0);

              }
              if(days!=0 && days!="" && $.isNumeric(days) && st_date!="")
              {
                    var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
                    var time= days*oneDay;
                    var st_date =$("#from_date").val();
                    var firstDate = new Date(st_date);
                    var firstDate_abs=Math.abs(firstDate);
                    var return_dt= time+firstDate_abs;
                    $("#return_date").datepicker( "update", new Date( return_dt ) );
              }
		  });
          var cust_type = $("#inquiry-customer_type").val();
		    if(cust_type==' . CustomerTypes::AGENT . '){
		        $(".agent_div").show();
		    }
		    else{
		        $(".agent_div").hide();
		    }
		$("#inquiry-customer_type").change(function(){
		    var cust_type = $(this).val();
		    if(cust_type==' . CustomerTypes::AGENT . '){
		        $(".agent_div").show();
		    }
		    else{
		        $(".agent_div").hide();
		    }
		});

           $(".age_child").keyup(function(){
                var value=$(this).val();
                if($.isNumeric(value) || value=="")
                {
                    $("#age_validate").hide();
                }
                else
                {
                    $("#age_validate").show();
                }
           });

       $("#inquiry-children_count").keyup(function(){
            var num= $(this).val();
            var data=[];
            var j=0;
            for(j=0;j<num;j++)
            {
                data[j]=$(".age_child").eq(j).val();
            }

            if(num!=0 && $.isNumeric(num)){
                $("#age > input:gt(0)").remove();
                for(var i=0; i<num ; i++)
                {
                    $(".age_child").first().clone().insertAfter("input.age_child:last").val(data[i]).attr("placeholder","Child "+(i+1)+" Age");
                }
                $("input.age_child:first").remove();
                $("#age").show();
            }
            else
            {
                $("#age").hide();
            }
       });
	   $("#inquiry-children_count").change(function(){
            var num= $(this).val();
            var data=[];
            var j=0;
            for(j=0;j<num;j++)
            {
                data[j]=$(".age_child").eq(j).val();
            }

            if(num!=0 && $.isNumeric(num)){
                $("#age > input:gt(0)").remove();
                for(var i=0; i<num ; i++)
                {
                    $(".age_child").first().clone().insertAfter("input.age_child:last").val(data[i]).attr("placeholder","Child "+(i+1)+" Age");
                }
                $("input.age_child:first").remove();
                $("#age").show();
            }
            else
            {
                $("#age").hide();
            }
       });


		$(".city").change(function(){
		    var city = $(this).val();
		     $.ajax({
                    type:"GET",
                    url: "' . Yii::$app->getUrlManager()->createUrl(['inquiry/search-agent']) . '",
                    data: {city: city},
                    dataType: "json",
                    success: function (data) {
                             //console.log("offer data",data);
                              $("#inquiry-agent_id").find("option").remove();
                                 $.each(data, function(key, value) {
                                      if(key!=""){
                                         $("#inquiry-agent_id").append($("<option></option>").attr("value", key).text(value));
                                     }
                                 });
                                  $("#inquiry-agent_id").prepend("<option value=\'\' selected=\'selected\'>Select Agent</option>");
                                  $("#inquiry-agent_id option:first").prop("disabled", "disabled");
                    }
            });
		});

	$("#add_inquiry").click(function(){
			var cust_type = $("#inquiry-customer_type").val();
			var source = $("#inquiry-source").val();
			var inq = $("#inquiry-inquiry_details").val();
					  var desc = inq.replace(/font-family:/g, "");
					  desc = desc.replace(/font-style:/g, "");
					  desc = desc.replace(/font-size:/g, "");
					  desc = desc.replace(/font-variant:/g, "");
					  desc = desc.replace(/font-weight:/g, "");
					  desc = desc.replace(/background-color:/g, "");
					  desc = desc.replace(/color:/g, "");
					  desc = desc.replace(/text-transform:/g, "");
					  desc = desc.replace(/text-decoration:/g, "");
					  desc = desc.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
					  desc = desc.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
					  desc = desc.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
					  desc = desc.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
					  desc = desc.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
            $("#inquiry-inquiry_details").summernote("code", desc);
			
			if(cust_type==' . CustomerTypes::AGENT . '){
			    var agent = $("#inquiry-agent_id").val();
			    if(agent==""){
			        $(".agent_error").show();
			        return false;
			    }
			    else{
			         $(".agent_error").hide();
			    }
			}
			if(source=='.SourceTypes::REFERENCE.'){
			    var ref = $("#inquiry-reference").val();
			    if(ref==""){
			         $(".field-inquiry-reference").removeClass("has-success");
			         $(".field-inquiry-reference").addClass("has-error");
			         $(".ref_error").show();
			          return false;
			    }
			    else{
			        $(".field-inquiry-reference").removeClass("has-error");
			         $(".field-inquiry-reference").addClass("has-success");
			          $(".ref_error").hide();
			         return true;
			    }
			}
	});
        $("#city").change(function() {
            var inputVal = $(this).val();
            var numericReg = /^[a-zA-Z]+(\s[a-zA-Z]+)?$/i;
            if(!numericReg.test(inputVal)) {
                $(this).val(" ");
		        $("span#select2-city-container").html(" ");
                $(".error").show();
            }
	        else
	        {
		        $(".error").hide();
	        }
        });

        $("form#agent-form").on("beforeSubmit", function (e) {
            e.preventDefault();
            var form = $(this);

            if (form.find(".has-error").length) {
                return false;
            }
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: form.serialize(),
                success: function(data) {
                    var response = $.parseJSON(data);
                    if (response.error) {
                        if (typeof response.errors.city_id != "undefined") {
                            alert(response.errors.city_id[0]);
                        }
                    } else {
                        $("#inquiry-agent_id").html(response.allAgents);
                        $(".bs-modal-sm").modal("toggle");
                        $(".modal-backdrop").remove();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });

            return false;
        });
 });
');