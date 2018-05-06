<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 02-07-2016
 * Time: 13:12
 */
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use common\models\City;
use common\models\Country;
use common\models\Currency;
use common\models\Package;
use common\models\PriceType;
use common\models\RoomType;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


$this->title = 'Send Quotation';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inquiries'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION])]];
$this->params['breadcrumbs'][] = ['label' => 'KR-' . $model->inquiry_id, 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $model->id])]];
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="page-title">
        <div class="title"><?= Html::encode($this->title) ?></div>
    </div>
    <ol class="breadcrumb">
        <li>
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index"); ?>">Dashboard</a>
        </li>
        <?php foreach ($this->params['breadcrumbs'] as $k => $v) {
            if (isset($v['label'])) {
                echo "<li><a href=" . $v['url'][0] . ">" . $v['label'] . "</a></li>";
            } else {
                echo "<li class='active ng-binding'>$v</li>";
            }
        }?>
    </ol>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]); ?>
<?php

$dataExp = <<< SCRIPT
          function (term, page) {
            var pac_country= $("#country-name").val();
			var pac_nights= $("#package-no_of_days_nights").val();
			var pac_city= $("#city-name").val();
			var pac_name= $("#package-name").val();
            return {
              search: term.term, // search term
              country:pac_country,
              nights:pac_nights,
              city:pac_city,
              name:pac_name,
            };
          }
SCRIPT;

$dataResults = <<< SCRIPT
          function (data, page) {
            return {
              results: data.results
            };
          }
SCRIPT;

?>
<?php  if($model->status==InquiryStatusTypes::AMENDED){ ?>
    <div class="card-header card-custom-height bg-primary text-white followup" data-target="#followup_panel">
        <h5>Followup Details  <span class="pull-right glyphicon glyphicon-plus followup-plus"> Show more..</span>
            <span class="pull-right glyphicon glyphicon-minus followup-minus">  Show less..</span></h5>
    </div>
    <div class="card bg-white">
        <div class="card-block" id="followup_panel">
            <?php foreach ($followup as $f) { ?>
                <div class="form-group row">
                    <?php if ($f->by != '') { ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Head:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $f->by0->username ?></label>
                        </div>
                    <?php } ?>
                    <?php if ($f->date != '') { ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Date:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= date('M-d-Y', $f->date) ?></label>
                        </div>
                    <?php } ?>
                    <?php if ($f->note != '') { ?>
                        <div class="col-sm-6">
                            <label class="control-label model-view-label">
                                <strong>Notes:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $f->note ?></label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php  }?>
    <div class="card-header card-custom-height bg-primary text-white inquiry" data-target="#inquiry_panel">
        <h5>Inquiry Details  <span class="pull-right glyphicon glyphicon-plus inquiry-plus"> Show more..</span>
            <span class="pull-right glyphicon glyphicon-minus inquiry-minus">  Show less..</span></h5>
    </div>
    <div class="card bg-white">
        <div class="card-block collapse" id="inquiry_panel">

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

            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'from_date')->textInput(['id' => 'from_date', 'class' => 'form-control', 'name' => 'Inquiry[from_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d', 'value' => date("M-d-Y", strtotime($model->from_date))]); ?>
                </div>

                <div class="col-sm-2">
                    <label class="control-label">No of Nights</label>
                    <?= $form->field($model, 'no_of_days')->dropDownList(range(0,12))->label(false) ?>
                </div>

                <div class="col-sm-3 col-sm-offset-1">
                    <?= $form->field($model, 'return_date')->textInput(['id' => 'return_date', 'class' => 'form-control', 'name' => 'Inquiry[return_date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d', 'value' => date("M-d-Y", strtotime($model->return_date))]); ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'destination')->textInput() ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'leaving_from')->textInput() ?>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">No of Rooms</label>
                    <?= $form->field($model, 'room_count')->dropDownList(range(0,12))->label(false) ?>
                </div>
                <div class="col-sm-3 col-sm-offset-1">
                    <label class="control-label">Room Type</label>
                    <?= Select2::widget([
                        'name' => 'room_type',
                        'id' => 'room',
                        'value' => $room_arr,//$room__type_name, // initial value
                        'data' => RoomType::getRoomTypes(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a RoomType', 'multiple' => true],
                        'pluginOptions' => ['tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2">
                    <label class="control-label">Adults</label>
                    <?= $form->field($model, 'adult_count')->dropDownList(range(0,12))->label(false) ?>
                </div>
                <div class="col-sm-2  custom-click">
                    <label class="control-label">Children</label>
                    <?= $form->field($model, 'children_count')->dropDownList(range(0,12))->label(false) ?>
                </div>
                <div class="col-sm-4 col-sm-offset-1">
                    <div id="age" class="age_dropdown text-center">
                        <?php if (count($child_age) > 0) {
                            foreach ($child_age as $val) {?>
                                <input class="age_child text-center"name="InquiryPackageChildAge[age][]" type="text" value=<?= $val ?> >
                            <?php }
                        } else {?>
                            <input class="age_child text-center" name="InquiryPackageChildAge[age][]" type="text"/>
                        <?php } ?>
                    </div>
                    <div id="age_validate" class="error-hint">Age must be an integer</div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label">Inquiry Details</label>
                    <?= $form->field($model, 'inquiry_details')->textarea(['class' => 'summernote'])->label(false) ?>
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
            <!--<div class="form-group row">
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
            </div>-->
        </div>
    </div>


<?php if ($model->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>

    <div class="card-header card-custom-height bg-primary text-white package" data-target="#package_panel">
        <h5>Package Details  <span class="pull-right glyphicon glyphicon-plus package-plus"> Show more..</span>
            <span class="pull-right glyphicon glyphicon-minus package-minus">  Show less..</span></h5>
    </div>
    <div class="card bg-white">
        <div class="card-block" id="package_panel">
            <div class="card-block margin-less ">
                <div class="row ">
                    <a id="Packagesearchbtn" class="btn btn-icon-icon btn-danger pull-right"><i class="fa fa-search"></i></a>
                </div>
            </div>
            <div id="Packagesearch">
                <div class="card bg-white">

                    <div class="card-header bg-danger-dark">
                        <strong class="text-white">Search</strong>
                    </div>

                    <div class="card-block">
                        <?php $form = ActiveForm::begin([
                           // 'action' => ['index'],
                            //'method' => 'get',
                        ]);

                        ?>
                        <div class="row">
                            <div class="col-md-3 ">
                                 <?= Select2::widget([
                                    'model'=> $country_model,
                                    'attribute' =>'name',
                                    'id' => 'country',
                                    'value' => '', // initial value
                                    'data' => Country::getCountries(),
                                    'maintainOrder' => true,
                                    'options' => ['placeholder' => 'Select a Country', 'multiple' => true],
                                    'pluginOptions' => ['tags' => false],
                                ]); ?>

                            </div>
                            <div class="col-md-3 ">
                                <?= $form->field($packagemodel, 'no_of_days_nights')->dropDownList(range(0, 25),['prompt'=>'Select No of Nights'])->label(false) ?>
                            </div>
                            <div class="col-md-3 ">
                                <?= Select2::widget([
                                    'model'=> $city_model,
                                    'attribute' =>'name',
                                    'id' => 'package_city0',
                                    'value' => '', // initial value
                                    'data' => City::getCityId(),
                                    'maintainOrder' => true,
                                    'options' => ['placeholder' => 'Select a City', 'multiple' => true,'class' => 'package_city'],
                                    'pluginOptions' => ['tags' => false],
                                ]); ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($packagemodel, 'for_agent')->dropDownList(['0'=>'Customer','1'=>'Agent'],['prompt'=>'Package for'])->label(false) ?>
                                </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($packagemodel, 'name')->textInput(['class'=>'resetsearch form-control','placeholder'=>'Package Name'])->label(false) ?>
                            </div>
                           <div class="col-sm-3">
                                <label class="cb-checkbox cb-md checked" id="check-label">
                                    <span class="cb-inner"><i><input type="checkbox" checked="checked" name="InquiryPackage[is_itinerary]" id="is_itinerary"></i></span>With Itinerary
                                </label>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-2 pull-right">
                                <?php echo Html::Button('Search', ['class' => 'btn btn-primary ','id'=>'addquotesearchbtn']) ?>
                                <?php // Html::resetButton('Reset', ['class' => 'btn btn-primary','id'=>'custom-reset']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>

                </div>
            </div>
            <div class="row">
                <div id="package-hint">
                    <?= $form->field($quotation_model, 'package_id')->hiddenInput()->label(false) ?>
                    <div id="package_hint" class="help-block error-hint">Package cannot be blank.</div>
                </div>
            </div>
            <div class="row">
                <div id="package-list">
                </div>
            </div>

           <!-- <div class="form-group row">
                <!--<div class="col-sm-4">
                    <label class="control-label">Package</label>
                    <?/*= Select2::widget([
                        'name' => 'InquiryPackage[package_id]',
                        'id' => 'inquirypackage-package_id',
                        'value' => $quotation_model->package_id, // initial value
                        'data' => Package::getItineraryPackage(),
                        'options' => ['placeholder' => 'Select Package'],
                        'pluginOptions' => ['tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10],
                    ]); */?>
                </div>-->
               <!-- <div class="col-sm-4">
                    <label class="control-label">Package</label>
                    <?/*= Select2::widget([
                        'name' => 'InquiryPackage[package_id]',
                        'id' => 'inquirypackage-package_id',
                        'value' => $quotation_model->package_id, // initial value
                        'data' => Package::getItineraryPackage(),
                        'options' => ['placeholder' => 'Select Package'],
                        'pluginOptions' => ['tags' => false,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10,
                        'ajax' => [
                        'url'=>  Yii::$app->getUrlManager()->createUrl(['inquiry/add-quote-search']),
                        'dataType' => 'json',
                        'data' => new JsExpression($dataExp),
                        'results' => new JsExpression($dataResults),
                    ],],
                    ]); */?>
                </div>

            </div>-->


            <div class="sk-circle center-block m-y-lg ajax-loaded" style="display: none;">
                <div class="sk-circle1 sk-child"></div>
                <div class="sk-circle2 sk-child"></div>
                <div class="sk-circle3 sk-child"></div>
                <div class="sk-circle4 sk-child"></div>
                <div class="sk-circle5 sk-child"></div>
                <div class="sk-circle6 sk-child"></div>
                <div class="sk-circle7 sk-child"></div>
                <div class="sk-circle8 sk-child"></div>
                <div class="sk-circle9 sk-child"></div>
                <div class="sk-circle10 sk-child"></div>
                <div class="sk-circle11 sk-child"></div>
                <div class="sk-circle12 sk-child"></div>
            </div>
            <div class="row">
                <div id="package-details"></div>
            </div>


        </div>
    </div>



<?php } else { ?>
    <div class="card-header card-custom-height bg-primary text-white hotel" data-target="#hotel_panel">
        <h5>Hotel Details  <span class="pull-right glyphicon glyphicon-plus hotel-plus"> Show more..</span>
            <span class="pull-right glyphicon glyphicon-minus hotel-minus">  Show less..</span></h5>
    </div>
    <div class="card bg-white">
        <div class="card-block" id="hotel_panel">
            <div class="form-group row">
                <label class="control-label">Hotel Details</label>
                <?= $form->field($quotation_model, 'hotel_details')->textarea(['id'=>'hotel_details','class' => 'summernote'])->label(false) ?>
            </div>
            <div id="hotel_hint" class=" help-block error-hint">Hotel details cannot be blank</div>
            <div class="form-group row">
                <div class=pull-right>
                    <?= Html::submitButton('Send Quotation', ['class' => 'btn btn-primary btn-round', 'id' => 'add_quotation']) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label">Email Cc</label>
                    <input type="text" class="form-control" name="email_cc">
                    <h6 class="error-hint">Note: Add Multiple Emails Separated by commas </h6>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Text</label>
        <textarea  class="form-control summernote"  id="customize_text" name="customize_text">
            Greetings from Travel Portal,
            Please find below your package details as required.
         </textarea>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<?php ActiveForm::end(); ?>
<?php

$this->registerJs('
 $(document).ready(function(){

   $(".dropdown-toggle").remove();
    $(".btn-codeview").remove();
 $(".inquiry-minus").hide();
  $(".followup-plus").hide();
 $(".package-plus").hide();
 $(".hotel-plus").hide();

 $(".followup").on("click", function (e) {
    var actives = $(this).attr("data-target");
    $(actives).slideToggle("collapse");
    $(".followup-minus,.followup-plus").toggle("hide");
});
 $(".inquiry").on("click", function (e) {
    var actives = $(this).attr("data-target");
    $(actives).slideToggle("collapse");
    $(".inquiry-minus,.inquiry-plus").toggle("hide");
});
 $(".package").on("click", function (e) {
    var actives = $(this).attr("data-target");
    $(actives).slideToggle("collapse");
    $(".package-minus,.package-plus").toggle("hide");
});
 $(".hotel").on("click", function (e) {
    var actives = $(this).attr("data-target");
    $(actives).slideToggle("collapse");
    $(".hotel-minus,.hotel-plus").toggle("hide");
});

$("#inquiry-quotation_manager").change(function(){
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
 });

 $("#inquiry-follow_up_head").change(function(){
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
 });


    var child_count=$("#inquiry-children_count").val()
    if(child_count == "")
    {
    $("#age").hide();
    }else
    {
    $("#age").show();
    }
    $("#age_validate").hide();

    var child_count = $("#inquiry-children_count").val();
        if(child_count!=0){
            $("#age").show();
        }
        else
        {
            $("#age").hide();
        }

    $("#package_hint").hide();
    $("#hotel_hint").hide();

    $("#is_itinerary").click(function(){
            if($(this).prop("checked") == true){
                $("#check-label").addClass("checked");
            }
            else if($(this).prop("checked") == false){
                $("#check-label").removeClass("checked");
            }
        });

 //var maskList = $.masksSort($.masksLoad("' . Url::base() . '/js/phone-codes.json"), ["#"], /[0-9]|#/, "mask");

    $(".age_child").keyup(function(){
           var value=$(this).val();
           if($.isNumeric(value) || value =="")
           {
                $("#age_validate").hide();
           }else
           {
                $("#age_validate").show();
           }
    });


 //   $("#Packagesearch").hide();

$("#Packagesearchbtn").click(function(){

$("#Packagesearch").slideToggle("hide");

});

$("#custom-reset").click(function(){

$(".resetsearch").val("");

$("select option:selected").removeAttr("selected");


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
        $(".age_child").keyup(function(){
            var value=$(this).val();
            if($.isNumeric(value) || value=="")
            {
                $("#age_validate").hide();
            }else
            {
                $("#age_validate").show();
            }
        });
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
        $(".age_child").keyup(function(){
            var value=$(this).val();
            if($.isNumeric(value) || value=="")
            {
                $("#age_validate").hide();
            }else
            {
                $("#age_validate").show();
            }
        });
    });



    $("#addquotesearchbtn").click(function(){

            $(".ajax-loaded").show();
            $("#package-details").hide();
            $("#package-list").show();
            var pac_country= $("#country-name").val();
			var pac_nights= $("#package-no_of_days_nights").val();
			var pac_city= $("#city-name").val();
			var pac_name= $("#package-name").val();
			var pac_for= $("#package-for_agent").val();

			 $.ajax({
                    type:"GET",
                    url: "' . Yii::$app->getUrlManager()->createUrl(['inquiry/add-quote-search']) . '",
                    data: {country:pac_country,nights:pac_nights,city:pac_city,name:pac_name,for_agent:pac_for},
                    success: function (data) {
                        $(".ajax-loaded").hide();
                        $("#package-list").html(data);
                          $(".dropdown-toggle").remove();
                           $(".btn-codeview").remove();
                         $(".package-header").click(function(){
                            $(".ajax-loaded").show();
                            var itinerary_flag=$("#is_itinerary").prop("checked");
                            var package_id = $(this).data("id");
                            $("#inquirypackage-package_id").val(package_id);
                             $.ajax({
                                        type:"GET",
                                        url: "' . Yii::$app->getUrlManager()->createUrl(['inquiry/search-model']) . '",
                                        data: {package_id: package_id,itinerary_flag:itinerary_flag},
                                        success: function (data) {
                                                $(".ajax-loaded").hide();
                                                $("#package-details").show();
                                                 $("#package-details").html(data);
                                                 $(".note").summernote();
                                                 $(".summernote").summernote();
                                                   $(".dropdown-toggle").remove();
                                                    $(".btn-codeview").remove();
                                                  $("#package-list").hide();

                                                 /* $("#package-no_of_days_nights").TouchSpin({initval: 0, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary"});
                                                   $("#package_days").TouchSpin({initval: 0, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary"});
                                                    $("#itinerary-no_of_itineraries").TouchSpin({initval: 0, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary"});*/
                                               $(".package-banner").innerHTML = "";


											 function previewImage(){
												$(".package-banner").off("change").on("change",function(){
													var that = $(this);

													//var input_data_media = $(that).attr("data-media");
													if (this.files && this.files[0]) {
														var reader = new FileReader();
														//console.log(id);
														reader.onload = function (e) {
															//$this.parent().parent().find(".package-image").attr("src", e.target.result);
															$(that).parent().parent().find("img").attr("src", e.target.result);

														}
														reader.readAsDataURL(this.files[0]);
													}
												});
											 }
											 previewImage();

                                              /*get previous images*/
                                            var default_image= "' . Url::to("@web/images/image.jpg", true) . '";
                                            var image_al=[];
                                            for(j=0; j<= $(".p_nights").val();j++){
                                                image_al[j]=$(".package-image").eq(j).attr("src");
                                            }
                                              $(".p_nights").change(function(){
                                                 var days = $(this).val();
                                                  var image=[];
                                                   var data=[];
                                                   var title=[];

                                                   var j=0;
                                                   /*for(j=0; j<= days;j++){
                                                        title[j]="Day "+(j+1);
                                                   }*/

                                                   for(j=0; j<= days;j++){
                                                        data[j]= $(".note").eq(j).summernote("code");

                                                        if($(".itinerary_title").eq(j).val() == null){

                                                              title[j]="Day "+(j+1) + " - ";
                                                        }
                                                        else
                                                        {
                                                            title[j]= $(".itinerary_title").eq(j).val();

                                                        }
                                                         if(image_al[j] == undefined){

                                                       image[j]=default_image;
                                                        }
                                                        else
                                                        {
                                                         image[j]=image_al[j];

                                                        }
                                                    }

                                                    $(".itinerary-parent > div:gt(0)").remove();

                                                    for(var i = 0; i <= days; i++){
                                                       $(".itinerary-child").first().find(".note").summernote("destroy");
                                                       $(".itinerary-child").first().find(".package-image").attr("src",default_image);
                                                       $(".itinerary-child").first().clone().insertAfter("div.itinerary-child:last").find(".note").summernote("code",data[i]);
                                                       $(".itinerary-child").last().find(".itinerary_title").val(title[i]);
                                                       $(".itinerary-child").last().find(".package-banner").attr("name","Itinerary[media_id]["+[i]+"]");
                                                       $(".itinerary-child").last().find(".note").attr("id","description"+ i);
                                                       $(".itinerary-child").last().find(".package-image").attr("src",image[i]);
                                                                }
                                                   $(".itinerary-child").first().remove();
                                                    previewImage();
                                                     $(".dropdown-toggle").remove();
                                                    $(".btn-codeview").remove();
                                              });

                                              $(".package-price").change(function(){
                                                    var val = $(this).val();
                                                    if(!$.isNumeric(val) || val<0){
                                                        $(".price_error").show();
                                                    }
                                                    else{
                                                         $(".price_error").hide();
                                                    }
                                              });


                                                    $(".kv-plugin-loading").hide();
                                                   //$(".package_city").select2("destroy");
                                                    $(".package_city").select2({
                                                        tags: true,
                                                        width: "350px",
                                                    });

                                                   $(".package_country").select2({
                                                        tags: true,
                                                        width: "350px",
                                                    });

                                                $(".city-plus").click(function(){
                                                        var minus_div = "<div class=\'col-sm-1\'><br/><a class=\'fa fa-minus-circle fa-2x price-icon city-minus\'></a></div>"
                                                        var numCity = $(".package_city").length;
                                                        $(".package_city").select2("destroy");
                                                        $(".city-child").first().clone(true).insertAfter("div.city-child:last");
                                                        $(".city-child").last().find(".nights").attr("id","nights"+numCity);
                                                        $(".city-child").last().find(".package_city").attr("id","package_city"+numCity);
                                                         $(".city-child").last().append(minus_div);
                                                          $(".package_city").select2({
                                                            tags: true,
                                                              width: "350px",
                                                        });

                                                         $(".city-minus").click(function(){
                                                            var len = $(".city-child").length;
                                                            if(len>1)
                                                                $(".city-child").last().remove();
                                                        });
                                                      });


                                                 $(".package_city").change(function(){
                                                        var numCity = $(".package_city").length;

                                                        var numNights = $(".nights").length;
                                                        var name="";
                                                        for(var i=0;i<numCity;i++){
                                                            var city = $("#package_city"+i).val();
                                                            var night = $("#nights"+i).val();

                                                            if(city!="" && typeof city != "undefined" && typeof night != "undefined")
                                                            {
                                                                name=name+night+" "+city+" - ";
                                                            }
                                                        }
                                                        $("#package-itinerary_name").val(name.slice(0,-3));
                                                    });

                                                 $(".nights").change(function(){
                                                        var numCity = $(".package_city").length;
                                                        var numNights = $(".nights").length;
                                                        var name="";
                                                        for(var i=0;i<numCity;i++){
                                                            var city = $("#package_city"+i).val();
                                                            var night = $("#nights"+i).val();

                                                            if(city!="" && typeof city != "undefined" && typeof night != "undefined")
                                                            {
                                                                name=name+night+" "+city+" - ";
                                                            }
                                                        }
                                                        $("#package-itinerary_name").val(name.slice(0,-3));
                                                    });
                                                    $(".p_nights").change(function(){
                                                        var nights =  $(this).val();
                                                        $("#package_days").html(+nights+1);
                                                    });

                                                //$(".price_type").select2("destroy");
                                                $(".price_type").select2({
                                                    tags: true,
                                                      width: "200px",
                                                });

                                             $(".plus").click(function(){
                                                var minus_div = "<div class=\'col-sm-1\'><br/><a class=\'fa fa-minus-circle fa-2x price-icon minus\'></a></div>"
                                                 var numType = $(".price_type").length;
                                                $(".price_type").select2("destroy");
                                                $(".child-form").first().clone(true).insertAfter("div.child-form:last");
                                                $(".child-form").last().find(".price_type").attr("id","price_type"+numType);
                                                $(".child-form").last().append(minus_div);
                                                $(".price_type").select2({
                                                    tags: true,
                                                    width: "200px",
                                                });
                                                    $(".minus").click(function(){
                                                        var len = $(".child-form").length;
                                                        if(len>1)
                                                            $(".child-form").last().remove();
                                                    });
                                                });
												
												$("#add_quotation").click(function(){

													var org_inc = $("#package-package_include").val();
													var inc = org_inc.replace(/font-family:/g, "");
														inc = inc.replace(/font-style:/g, "");
														inc = inc.replace(/font-size:/g, "");
														inc = inc.replace(/font-weight:/g, "");
														inc = inc.replace(/background-color:/g, "");
														inc = inc.replace(/color:/g, "");
														inc = inc.replace(/text-transform:/g, "");
														inc = inc.replace(/text-decoration:/g, "");
														inc = inc.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
														$("#package-package_include").summernote("code", inc);


                                                     var custom_text = $("#customize_text").val();
													var inc = custom_text.replace(/font-family:/g, "");
														inc = inc.replace(/font-style:/g, "");
														inc = inc.replace(/font-size:/g, "/font-size:16px/g");
														inc = inc.replace(/font-weight:/g, "");
														inc = inc.replace(/background-color:/g, "");
														inc = inc.replace(/color:/g, "");
														inc = inc.replace(/text-transform:/g, "");
														inc = inc.replace(/text-decoration:/g, "");
														inc = inc.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
														inc = inc.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
														$("#customize_text").summernote("code", inc);

													var org_ex = $("#package-package_exclude").val();
													var ex = org_ex.replace(/font-family:/g, "");
														ex = ex.replace(/font-style:/g, "");
														ex = ex.replace(/font-size:/g, "");
														ex = ex.replace(/font-variant:/g, "");
														ex = ex.replace(/font-weight:/g, "");
														ex = ex.replace(/background-color:/g, "");
														ex = ex.replace(/color:/g, "");
														ex = ex.replace(/text-transform:/g, "");
														ex = ex.replace(/text-decoration:/g, "");
														ex = ex.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
														ex = ex.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
														ex = ex.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
														ex = ex.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
														ex = ex.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
														$("#package-package_exclude").summernote("code", ex);

													var org_terms = $("#package-terms_and_conditions").val();
													var terms = org_terms.replace(/font-family:/g, "");
													    terms = terms.replace(/font-style:/g, "");
														terms = terms.replace(/font-size:/g, "");
														terms = terms.replace(/font-variant:/g, "");
														terms = terms.replace(/font-weight:/g, "");
														terms = terms.replace(/background-color:/g, "");
														terms = terms.replace(/color:/g, "");
														terms = terms.replace(/text-transform:/g, "");
														terms = terms.replace(/text-decoration:/g, "");
														terms = terms.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
														terms = terms.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
														terms = terms.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
														terms = terms.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
														terms = terms.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
														$("#package-terms_and_conditions").summernote("code", terms);




													var org_info = $("#package-other_info").val();
													var info = org_info.replace(/font-family:/g, "");
														info = info.replace(/font-style:/g, "");
														info = info.replace(/font-size:/g, "");
														info = info.replace(/font-variant:/g, "");
														info = info.replace(/font-weight:/g, "");
														info = info.replace(/background-color:/g, "");
														info = info.replace(/color:/g, "");
														info = info.replace(/text-transform:/g, "");
														info = info.replace(/text-decoration:/g, "");
														info = info.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
														info = info.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
														info = info.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
														info = info.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
														info = info.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
														$("#package-other_info").summernote("code", info);

													var count = $("#package-no_of_days_nights").val();
														for(var i=0;i<count; i++){
															var org_desc = $("#description"+i).val();
															var desc = org_desc.replace(/font-family:/g, "");
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
																$("#description"+i).summernote("code", desc);
														}
												});		

                                    }
                            });
                        });
                    }
             });


    });

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
                var firstDate = new Date(st_date);
                var firstDate_abs=Math.abs(firstDate);
                var return_dt= time+firstDate_abs;
                $("#return_date").datepicker( "update", new Date( return_dt ) );
		    }
		  });

	$("#add_quotation").click(function(){
        var type ='.$model->type.';
   
     if(type=='. InquiryTypes::PER_ROOM_PER_NIGHT.')
     {
        var a=$("#hotel_details").val();
	    if(a=="")
	    {
	    	$("#hotel_hint").show();
	    	return false;
	    }else
	    {
            $("#hotel_hint").hide();
            var org_info = $("#hotel_details").val();
				var info = org_info.replace(/font-family:/g, "");
					info = info.replace(/font-style:/g, "");
					info = info.replace(/font-size:/g, "");
					info = info.replace(/font-variant:/g, "");
					info = info.replace(/font-weight:/g, "");
					info = info.replace(/background-color:/g, "");
					info = info.replace(/color:/g, "");
					info = info.replace(/text-transform:/g, "");
					info = info.replace(/text-decoration:/g, "");
					info = info.replace(/<[\/]{0,1}(b)[^><]*>/ig,"");
					info = info.replace(/<[\/]{0,1}(strong)[^><]*>/ig,"");
					info = info.replace(/<[\/]{0,1}(u)[^><]*>/ig,"");
					info = info.replace(/<[\/]{0,1}(h1)[^><]*>/ig,"");
					info = info.replace(/<[\/]{0,1}(h2)[^><]*>/ig,"");
            $("#hotel_details").summernote("code", info);
            return true;
	    }
     }
	});

 });
', \yii\web\View::POS_END);
?>