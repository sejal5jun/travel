<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 01-07-2016
 * Time: 18:55
 */
use backend\models\enums\CategoryTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\PackageTypes;
use backend\models\enums\PricingTypes;
use common\models\Currency;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(
    [
        'options' => [
            'enctype' => 'multipart/form-data',
        ]
    ]
); ?>
    <div class="card bg-white">

        <div class="card-header bg-danger-dark-dark">
            <strong class="text-white">Package Details</strong>
        </div>

        <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <?= $form->field($model, 'name')->textInput() ?>
                    </div>

                    <div class="col-sm-4 col-sm-offset-1 custom-display">
                        <div class="custom-width">
                            <?= $form->field($model, 'no_of_days_nights')->textInput(['class' => 'form-control bl0 br0 spinner1'])->label("Nights") ?>

                        </div>
                        <div class="custom-width">
                            <label class="control-label">Days</label>
                            <input type="text" id="package_days" class="form-control bl0 br0 spinner1">
                        </div>
                    </div>

                    <div class="col-sm-2 col-sm-offset-1">
                        <?= $form->field($model, 'category')->dropDownList(CategoryTypes::$headers, [$model->category => ['Selected' => 'selected']]); ?>
                    </div>
                </div>

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
                        <label class="control-label">Terms And Conditions</label>
                        <?= $form->field($model, 'terms_and_conditions')->textarea(['class' => 'summernote'])->label(false) ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label">Other Info</label>
                        <?= $form->field($model, 'other_info')->textarea(['class' => 'summernote'])->label(false) ?>
                    </div>
                </div>
        </div>
    </div>

    <div class="card bg-white itinerary-div">

        <div class="card-header bg-danger-dark-dark">
            <strong class="text-white">Itinerary Details</strong>
        </div>

        <div class="card-block">

                <?php if (isset($itinerary[0])) { ?>
                    <div class="itinerary-parent">

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <?= $form->field($itinerary[0], 'name')->textInput() ?>
                            </div>

                            <div class="col-sm-4">
                                <?= $form->field($itinerary[0], 'no_of_itineraries')->textInput(['class' => 'form-control bl0 br0 spinner1']) ?>
                            </div>
                        </div>

                        <?php $count = count($itinerary);
                        for ($i = 0; $i < $count; $i++) {
                            $it = $itinerary[$i];?>
                            <div class="form-group row itinerary-child">
                                <div class="col-sm-2 img-div">
                                    <img
                                        src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($model->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                                        class="package-image image responsive" width="100" height="100"
                                        alt="Package banner"/>
                                    <?= $form->field($it, "media_id[$i]")->fileInput(['class' => 'package-banner', 'data-itinerary' => $it->id, 'data-media' => $it->media_id])->label(false); ?>
                                </div>

                                <div class="col-sm-4">
                                    <?= $form->field($it, 'title[]')->textInput(['class' => 'form-control itinerary_title', 'value' => $it->title]) ?>
                                </div>

                                <div class="col-sm-6">
                                    <label class="control-label">Description</label>
                                    <?= $form->field($it, 'description[]')->textarea(['class' => 'note', 'value' => $it->description, 'id' => "description$i"])->label(false) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php
                } else {
                    ?>
                    <div class="itinerary-parent">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <?= $form->field($new_itinerary, 'name')->textInput() ?>
                            </div>

                            <div class="col-sm-4">
                                <?= $form->field($new_itinerary, 'no_of_itineraries')->textInput(['class'=>'form-control bl0 br0 spinner1']) ?>
                            </div>
                        </div>
                        <div class="form-group row itinerary-child">
                            <div class="col-sm-2 img-div">
                                <img
                                     src="<?php echo Url::to('@web/images/image.jpg', true); ?> "
                                     class="package-image image responsive" id="img0" width="100" height="100"
                                     alt="Package banner"/>
                                <?= $form->field($new_itinerary, 'media_id[0]')->fileInput(['id' => 'media', 'class' => 'package-banner'])->label(false); ?>
                            </div>

                            <div class="col-sm-10">
                                <?= $form->field($new_itinerary, 'title[]')->textInput(['class' => 'form-control itinerary_title']) ?>
                                <label class="control-label">Description</label>
                                <?= $form->field($new_itinerary, 'description[]')->textarea(['class' => 'note', 'id' => "description0"])->label(false) ?>
                            </div>

                        </div>
                    </div>
                <?php } ?>
        </div>
    </div>

    <div class="card bg-white">

        <div class="card-header bg-danger-dark-dark">
            <strong class="text-white">Pricing Details</strong>
        </div>

        <div class="card-block">

            <?php if (count($price_model) > 0) { ?>
                <div class="parent-form">
                    <?php $count = count($price_model);
                    for ($i = 0; $i < $count; $i++) {
                        $pm = $price_model[$i];?>
                        <div class="form-group row child-form">
                            <div class="col-sm-3">
                                <label class="control-label">Currency</label>
                                <?= $form->field($pm, 'currency_id[]')->dropDownList(Currency::getCurrency(), ['options' => [$pm->currency_id => ['Selected' => true]]])->label(false); ?>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">Type</label>
                                <?= $form->field($pm, 'type[]')->dropDownList(PricingTypes::$headers, ['options' => [$pm->type => ['Selected' => 'selected']]])->label(false); ?>
                            </div>

                            <div class="col-sm-3">
                                <?= $form->field($pm, 'price[]')->textInput(['class' => 'form-control package-price', 'value' => $pm->price]) ?>
                                <div class="price_error help-block error-hint" style="display:none;">Price must be a
                                    positive number.
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <a class="fa fa-plus-circle fa-2x price-icon plus"></a>
                            </div>

                            <?php if ($i != 0) { ?>
                                <div class="col-sm-1">
                                    <a class="fa fa-minus-circle fa-2x price-icon minus"></a>
                                </div>
                            <?php } ?>

                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>

                <div class="parent-form">

                    <div class="form-group row child-form">
                        <div class="col-sm-3">
                            <label class="control-label">Currency</label>
                            <?= $form->field($new_price_model, 'currency_id[]')->dropDownList(Currency::getCurrency())->label(false); ?>
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">Type</label>
                            <?= $form->field($new_price_model, 'type[]')->dropDownList(PricingTypes::$headers)->label(false); ?>
                        </div>

                        <div class="col-sm-3">
                            <br/>
                            <?= $form->field($new_price_model, 'price[]')->textInput(['class' => 'form-control package-price']) ?>
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

            <?php } ?>
            <div class="form-group row">
                <?= Html::submitButton('Update Package', ['class' => 'btn btn-primary btn-round pull-right btn-sub btn-lg']) ?>
            </div>


        </div>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs('
 $("document").ready(function(){
  var type =$(".package-type").val();
        if(type==' . PackageTypes::PACKAGE_WITHOUT_ITINERARY . '){
            $(".itinerary-div").hide();
        }
        else{
             $(".itinerary-div").show();
        }
 $(".package-type").change(function(){
        var type =$(this).val();
        if(type==' . PackageTypes::PACKAGE_WITHOUT_ITINERARY . '){
            $(".itinerary-div").hide();
        }
        else{
             $(".itinerary-div").show();
        }
    })

$(".note").summernote();
 var nights =  $("#package-no_of_days_nights").val();
      var no_of_days =  $("#package_days").val();

      $("#package_days").val(+nights+1);


$(".package-banner").innerHTML = "";
      function previewImage(){
        $(".package-banner").off("change").on("change",function(){
            var that = $(this);


            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    //$this.parent().parent().find(".package-image").attr("src", e.target.result);
                    $(that).parent().parent().find("img").attr("src", e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
     }
     previewImage();


    $("#package-no_of_days_nights").change(function(){
          var nights =  $("#package-no_of_days_nights").val();
          var no_of_days =  $("#package_days").val();
          $("#package_days").val(+nights+1);
         });

     $("#package_days").change(function(){
         var nights =  $("#package-no_of_days_nights").val();
          var no_of_days =  $("#package_days").val();
         $("#package-no_of_days_nights").val(no_of_days - 1);
     });

       $("#itinerary-no_of_itineraries").change(function(){
           var days = $(this).val();
           var default_image= "' . Url::to("@web/images/image.jpg", true) . '";
           var image=[];
		   var data=[];
	       var title=[];
           var a=[];
           var j=0;
           for(j=0; j<= days;j++){
           title[j]="Day "+(j+1);
           }

           for(j=0; j< days;j++){
                data[j]= $(".note").eq(j).summernote("code");
                if( a != "")
                {
                    title[j]= $(".itinerary_title").eq(j).val();
                }else
                {
                   title[j]="Day "+(j+1);
                }
                image[j]=$(".package-image").eq(j).attr("src");
            }

            $(".itinerary-parent > div:gt(1)").remove();

            for(var i = 0; i <= days; i++){

               $(".itinerary-child").first().find(".note").summernote("destroy");
               $(".itinerary-child").first().find(".package-image").attr("src",default_image);

               $(".itinerary-child").first().clone().insertAfter("div.itinerary-child:last").find(".note").summernote("code",data[i]);
               $(".itinerary-child").last().find(".itinerary_title").val(title[i]);
               $(".itinerary-child").last().find(".package-banner").attr("name","Itinerary[media_id]["+[i]+"]");
               $(".itinerary-child").last().find(".note").attr("id","description"+i);
               $(".itinerary-child").last().find(".package-image").attr("src",image[i]);
            }
            $(".itinerary-child").first().remove();
            previewImage();
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


   $(".plus").click(function(){
        var minus_div = "<div class=\'col-sm-1\'><a class=\'fa fa-minus-circle fa-2x price-icon minus\'></a></div>"
        $(".child-form").first().clone(true).insertAfter("div.child-form:last");
         $(".child-form").last().append(minus_div);

          $(".minus").click(function(){
            $(".child-form").last().remove();
	    });
	  });

	   $(".btn-sub").click(function(){
        var org_inc = $("#package-package_include").val();
         var inc = org_inc.replace(/font-family:/g, "");
         $("#package-package_include").summernote("code", inc);

         var org_ex = $("#package-package_exclude").val();
         var ex = org_ex.replace(/font-family:/g, "");
         $("#package-package_exclude").summernote("code", ex);

         var org_terms = $("#package-terms_and_conditions").val();
         var terms = org_terms.replace(/font-family:/g, "");
         $("#package-terms_and_conditions").summernote("code", terms);

        var org_info = $("#package-other_info").val();
         var info = org_info.replace(/font-family:/g, "");
         $("#package-other_info").summernote("code", info);

         var count = $("#itinerary-no_of_itineraries").val();
         for(var i=0;i<count; i++){
             var org_desc = $("#description"+i).val();
            var desc = org_desc.replace(/font-family:/g, "");
            $("#description"+i).summernote("code", desc);
         }
    });


 });
');
