<?php

use backend\models\enums\CategoryTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\PackageTypes;
use backend\models\enums\PricingTypes;
use common\models\City;
use common\models\Country;
use common\models\Currency;
use common\models\PriceType;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Package */
/* @var $itinerary common\models\Itinerary */
/* @var $price_model common\models\PackagePricing */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]
); ?>
    <div class="card bg-white">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Package Details</strong>
        </div>

        <div class="card-block">

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label">Country</label>
                    <?= Select2::widget([
                        'name' => 'package_country',
                        'id' => 'country',
                        'value' => '', // initial value
                        'data' => Country::getCountries(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a Country', 'multiple' => true],
                        'pluginOptions' => ['tags' => false],
                    ]); ?>
                </div>
                <div class="col-sm-offset-1 col-sm-2  custom-display">
                    <div class="custom-width">
                        <label class="control-label">Nights</label>
                        <?= $form->field($model, 'no_of_days_nights')->dropDownList(range(0, 25))->label(false) ?>
                    </div>
                    <div class="custom-width">
                        <label class="control-label">Days</label><br/>
                        <label id="package_days" class="control-label"></label>
                    </div>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-3">

                    <?= $form->field($model, 'validity')->textInput(['id' => 'from_validity', 'class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    <h5 class="from_validity_error error-hint">From Validity cannot be blank</h5>
                </div>
                <div class="col-sm-3">

                    <?= $form->field($model, 'till_validity')->textInput(['id' => 'to_validity', 'class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    <h5 class="to_validity_error error-hint">To Validity cannot be blank</h5>
                </div>
                <div class="col-sm-3 col-sm-offset-2">
                    <label class="control-label">Package for:</label><br/>
                    <label class="cb-checkbox cb-md" id="check-label-agent">
                        <span class="cb-inner"><i><input type="checkbox"  name="for_agent" id="for_agent"></i></span>Agent
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="cb-checkbox cb-md checked" id="check-label-customer">
                        <span class="cb-inner"><i><input type="checkbox" checked="checked" name="for_customer" id="for_customer"></i></span>Customer
                    </label>
                </div>
                </div>
            <div class="form-group row city-child">
                <div class="col-sm-4">
                    <label class="control-label">City</label><br />
                    <?= Select2::widget([
                        'name' => 'package_city[]',
                        'id' => 'package_city0',
                        'value' => '', // initial value
                        'data' => City::getCity(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a City', 'multiple' => false,'class' => 'package_city'],
                        'pluginOptions' => ['tags' => true],
                    ]); ?>
                    <p class="theme_hint help-block">&nbsp You can also add a new City</p>
                    <p class='error error-hint help-block' style="display:none;">City cannot be a number.</p>
                </div>
                <div class="col-sm-1">
                    <label class="control-label">Nights</label><br/>
                    <?=Html::dropDownList('no_of_nights[]','', range(0, 25),['id' => 'nights0', 'class' => 'form-control nights'])?>
                </div>
                <div class="col-sm-1">
                    <br/>
                    <a class="fa fa-plus-circle fa-2x price-icon city-plus"></a>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'name')->textInput() ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'itinerary_name')->textInput()?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'category')->dropDownList(CategoryTypes::$headers, [$model->category => ['Selected' => 'selected']]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white itinerary-div">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Itinerary Details</strong>
        </div>

        <div class="card-block">
                <div class="itinerary-parent">

                    <div class="form-group row itinerary-child">
                        <div class="col-sm-2 img-div">
                            <img data-media="0"
                                 src="<?php echo Url::to('@web/images/image.jpg', true); ?> "
                                 class="package-image image responsive" id="img0" width="100" height="100"
                                 alt="Package banner"/>
                            <?php echo $form->field($itinerary_model, 'media_id[]')->fileInput(['id' => 'media', 'class' => 'package-banner', 'data-media' => '0'])->label(false); ?>
                        </div>

                        <div class="col-sm-8">
                            <?php echo $form->field($itinerary_model, 'title[]')->textInput(['class' => 'form-control itinerary_title', 'value' => 'Day 1 - ']) ?>
                            <label class="control-label">Description</label>
                            <?php echo $form->field($itinerary_model, 'description[]')->textarea(['class' => 'note', 'id' => 'description0'])->label(false) ?>
                        </div>
                    </div>

                </div>

        </div>
    </div>

    <div class="card bg-white">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Pricing Details</strong>
        </div>

        <div class="card-block">

            <div class="parent-form">

                <div class="form-group row child-form">
                    <div class="col-sm-3">
                        <label class="control-label">Currency</label>
                        <?= $form->field($price_model, 'currency_id[]')->dropDownList(Currency::getCurrency())->label(false); ?>
                    </div>

                    <div class="col-sm-3">
                            <label class="control-label">Price Type</label><br />
                            <?= Select2::widget([
                                'name' => 'PackagePricing[type][]',
                                'id' => 'price_type0',
                                'value' => '', // initial value
                                'data' => PriceType::getPriceTypesNames(),
                                'maintainOrder' => true,
                                'options' => ['placeholder' => 'Select a Type', 'multiple' => false,'class' => 'price_type'],
                                'pluginOptions' => ['tags' => true],
                            ]); ?>
                        <p class="theme_hint help-block">&nbsp You can also add a new Price Type</p>
                        <p class='type-error error-hint help-block' style="display:none;">Price Type cannot be a number.</p>
                    </div>

                    <div class="col-sm-3">
                        <?= $form->field($price_model, 'price[]')->textInput(['class' => 'package-price form-control']); ?>
                        <div class="price_error help-block error-hint" style="display:none;">Price must be a
                            positive number.
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <br/>
                        <a class="fa fa-plus-circle fa-2x price-icon plus"></a>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">

                    <?= $form->field($model, 'pricing_details')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label">Terms And Conditions</label>
                    <?= $form->field($model, 'terms_and_conditions')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

        </div>
    </div>

  <div class="card bg-white">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Other Details</strong>
        </div>
        <div class="card-block">

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label">Package Includes</label>
                    <?= $form->field($model, 'package_include')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label">Package Excludes</label>
                    <?= $form->field($model, 'package_exclude')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label">Additional Details</label>
                    <?= $form->field($model, 'other_info')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <?= Html::submitButton('Add New Package', ['class' => 'btn btn-primary btn-round pull-right btn-sub btn-lg','id'=>'new_package_btn']) ?>
                </div>
            </div>

        </div>


    </div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs('

 $("document").ready(function(){
    $(".from_validity_error").hide();
    $(".to_validity_error").hide();
      $(".dropdown-toggle").remove();
    $(".btn-codeview").remove();
    $(".package-type").change(function(){
        var type =$(this).val();
        if(type==' . PackageTypes::PACKAGE_WITHOUT_ITINERARY . '){
            $(".itinerary-div").hide();
            $("#itinerary-name").prop("disabled",true);
        }
        else{
             $(".itinerary-div").show();
              $("#itinerary-name").prop("disabled",false);
        }
    });

     $(".note").summernote();

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
      //$("#media").css("opacity",0);


     $("#package-no_of_days_nights").change(function(){

      var nights =  $("#package-no_of_days_nights").val();
      var no_of_days =  $("#package_days").val();

      $("#package_days").html(+nights+1);


     });

     $("#package_days").change(function(){

     var nights =  $("#package-no_of_days_nights").val();
      var no_of_days =  $("#package_days").val();
     $("#package-no_of_days_nights").val(no_of_days - 1);

     });

      $("#package-no_of_days_nights").change(function(){
        var days = $(this).val();
        $(".itinerary-parent > div:gt(0)").remove();

         for(var i = 0; i <= days; i++){
          $(".itinerary-child").first().find(".note").summernote("destroy");

            $(".itinerary-child").first().clone().insertAfter("div.itinerary-child:last");
                $(".itinerary-child").last().find(".itinerary_title").attr("value","Day "+(i+2)+" - ");
                 $(".itinerary-child").last().find(".note").attr("id","description"+(i+1));
                 $(".note").summernote();
        }
        $(".itinerary-child").last().remove();
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

    $(".price_type").select2("destroy");
    $(".price_type").select2({
        tags: true,
          width: "134px",
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
            width: "134px",
        });

          $(".minus").click(function(){
           var len = $(".child-form").length;
            if(len>1)
            $(".child-form").last().remove();
	    });
	  });

    $(".package_city").select2("destroy");
    $(".package_city").select2({
        tags: true,
          width: "134px",
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
             width: "134px",
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
            if(city!="")
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
            if(city!="")
            {
                name=name+night+" "+city+" - ";
            }
        }
        $("#package-itinerary_name").val(name.slice(0,-3));
    });

 $("#for_customer").click(function(){
            if($(this).prop("checked") == true){
                $("#check-label-agent").removeClass("checked");
                $("#check-label-customer").addClass("checked");
            }
            else if($(this).prop("checked") == false){
                 $("#check-label-customer").removeClass("checked");
                $("#check-label-agent").addClass("checked");
            }
        });

         $("#for_agent").click(function(){
            if($(this).prop("checked") == true){
                $("#check-label-agent").addClass("checked");
                $("#check-label-customer").removeClass("checked");
            }
            else if($(this).prop("checked") == false){
                $("#check-label-customer").addClass("checked");
                $("#check-label-agent").removeClass("checked");
            }
        });

          $("#from_validity").change(function(){
			var from =$("#from_validity").val();
			var to =$("#to_validity").val();
                var from_time = new Date(from).getTime();
                var to_time = new Date(to).getTime();
             if (from_time > to_time) {
                    $("#to_validity").datepicker("setDate",from);
                }
                $("#to_validity").datepicker("setStartDate",from);
                       	});
    $("#to_validity").change(function(){
         var from = $("#from_validity").val();
         var to = $(this).val();
         $("#from_validity").datepicker("setEndDate",to);

        });



    $(".btn-sub").click(function(){
    var from =$("#from_validity").val();
    var to =$("#to_validity").val();
    if(from== "" && to !="" || from != "" && to =="")
    {
    if(from== "" && to !="" ){
    $(".form_validity_error").show();
    $(".to_validity_error").hide();
        return false;
    }
    if(from != "" && to =="")
    {
    $(".to_validity_error").show();
    $(".from_validity_error").hide();
        return false;
    }
    return false;
    }
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
         for(var i=0;i<=count; i++){
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
 });
');
