<?php

use backend\models\enums\CategoryTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\PackageTypes;
use backend\models\enums\PricingTypes;
use common\models\City;use common\models\Country;use common\models\Currency;
use common\models\PriceType;
use kartik\select2\Select2;use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\ListView;



/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packages'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("package/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Packages</div>
    <div class="sub-title"><?= Html::encode($this->title) ?></div>
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

<div class="card bg-white">
    <div class="card-header">
        <?= Html::a(Yii::t('app', 'Update'),'javascript:void(0);', ['class' => 'btn btn-primary btn-round btn-update',]);?>
        <?php
        if ($model->status == 10) {
            echo Html::a(Yii::t('app', 'Delete'), '#', [
                'class' => 'btn btn-danger btn-round swal-warning-confirm',
                'data' => [
                    'url' => Yii::$app->getUrlManager()->createUrl(['/package/delete', 'id' => $model->id])
                ],
            ]);
        } else {
            echo Html::a(Yii::t('app', 'Activate'), '#', [
                'class' => 'btn btn-success btn-round swal-activate',
                'data' => [
                    'url' => Yii::$app->getUrlManager()->createUrl(['/package/delete', 'id' => $model->id])
                ],
            ]);
        }
        ?>
        <?= Html::a(Yii::t('app', 'Export to Pdf'), Yii::$app->getUrlManager()->createUrl(['/package/export-in-pdf', 'id' => $model->id]), ['class' => 'btn btn-success btn-round btn-export', 'target' => '_blank']);?>
    </div>
    
    <?php echo Yii::$app->controller->renderPartial('_view_package', [
            'model' => $model,
             ]);
    ?>


    <div class="update-div"  style="display: none">
        <?php $form = ActiveForm::begin([
               'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]); ?>
        <div class="card-header bg-danger-dark">
            <strong class="text-white">Package Details</strong>
        </div>
        <div class="card-block">

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label">Country</label>
                    <?php $country=[];

                    foreach($model->packageCountries as $val)
                    {
                        $country[] = $val->country_id;

                    }

                    ?>
                    <?= Select2::widget([
                        'name' => 'package_country',
                        'id' => 'country',
                        'value' =>$country, // initial value
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

                    <?= $form->field($model, 'validity')->textInput(['id' => 'from_validity', 'class' => 'form-control','data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    <h5 class="to_validity_error error-hint">To Validity cannot be blank</h5>
                </div>
                <div class="col-sm-3">

                    <?= $form->field($model, 'till_validity')->textInput(['id' => 'to_validity', 'class' => 'form-control','data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                    <h5 class="to_validity_error error-hint">To Validity cannot be blank</h5>
                </div>
                <div class="col-sm-3 col-sm-offset-2">
                    <label class="control-label">Package for:</label><br/>
                    <label class="cb-checkbox cb-md <?= ($model->for_agent == 1)?'checked':'' ?>" id="check-label-agent">
                        <span class="cb-inner"><i><input type="checkbox"  checked="<?= ($model->for_agent == 1)?'checked':'' ?>" name="for_agent" id="for_agent"></i></span>Agent
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="cb-checkbox cb-md <?= ($model->for_agent == 0)?'checked':'' ?>" id="check-label-customer">
                        <span class="cb-inner"><i><input type="checkbox" checked="<?= ($model->for_agent == 0)?'checked':'' ?>"  name="for_customer" id="for_customer"></i></span>Customer
                    </label>
                </div>
                </div>
            <?php
            $city=[];
            $city_nights=[];
            foreach($model->packageCities as $val)
            {
                $city[] = $val->city->name;
                $city_nights[] = $val->no_of_nights;

            }

               if(count($city) > 0){
            for($i=0; $i<count($city); $i++){ ?>


                <div class="form-group row city-child">
                    <div class="col-sm-4">
                        <label class="control-label">City</label><br />
                        <?= Select2::widget([
                            'name' => 'package_city[]',
                            'id' => "package_city".$i,
                            'value' => $city[$i], // initial value
                            'data' => City::getCity(),
                            // 'maintainOrder' => true,
                            'options' => ['placeholder' => 'Select a City', 'multiple' => false,'class' => 'package_city'],
                            'pluginOptions' => ['tags' => true],
                        ]); ?>
                        <p class="theme_hint help-block">&nbsp You can also add a new City</p>
                        <p class='error error-hint help-block' style="display:none;">City cannot be a number.</p>
                    </div>
                    <div class="col-sm-1">
                        <label class="control-label">Nights</label><br/>
                        <?= Html::dropDownList('no_of_nights[]',$city_nights[$i], range(0, 25),['id' => 'nights'.$i, 'class' => 'form-control nights'])?>
                    </div>
                    <div class="col-sm-1">
                        <br/>
                        <a class="fa fa-plus-circle fa-2x price-icon city-plus"></a>
                    </div>
                </div>
            <?php }
               }
               else {?>

                   <div class="form-group row city-child">
                       <div class="col-sm-4">
                           <label class="control-label">City</label><br />
                           <?= Select2::widget([
                               'name' => 'package_city[]',
                               'id' => "package_city0",
                               'value' => '', // initial value
                               'data' => City::getCity(),
                               // 'maintainOrder' => true,
                               'options' => ['placeholder' => 'Select a City', 'multiple' => false,'class' => 'package_city'],
                               'pluginOptions' => ['tags' => true],
                           ]); ?>
                           <p class="theme_hint help-block">&nbsp You can also add a new City</p>
                           <p class='error error-hint help-block' style="display:none;">City cannot be a number.</p>
                       </div>
                       <div class="col-sm-1">
                           <label class="control-label">Nights</label><br/>
                           <?= Html::dropDownList('no_of_nights[]',null, range(0, 25),['id' => 'nights0', 'class' => 'form-control nights'])?>
                       </div>
                       <div class="col-sm-1">
                           <br/>
                           <a class="fa fa-plus-circle fa-2x price-icon city-plus"></a>
                       </div>
                   </div>


            <?php } ?>

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

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Itinerary Details</strong>
        </div>
        <?php $count = count($itinerary);
                if($count > 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $it = $itinerary[$i]; ?>

                        <div class="card-block">
                            <div class="itinerary-parent">

                                <div class="form-group row itinerary-child">
                                    <div class="col-sm-2 img-div">
                                        <img
                                            src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($model->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                                            class="package-image image responsive" width="100" height="100"
                                            alt="Package banner"/>
                                        <?= $form->field($it, "media_id[$i]")->fileInput(['class' => 'package-banner', 'data-itinerary' => $it->id, 'data-media' => $it->media_id])->label(false); ?>
                                    </div>

                                    <div class="col-sm-8">
                                        <?= $form->field($it, 'title[]')->textInput(['class' => 'form-control itinerary_title', 'value' => $it->title]) ?>
                                        <label class="control-label">Description</label>
                                        <?= $form->field($it, 'description[]')->textarea(['class' => 'note', 'value' => $it->description, 'id' => "description$i"])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                }
        else
        {
        ?>
        <div class="card-block">
            <div class="itinerary-parent">

                <div class="form-group row itinerary-child">
                    <div class="col-sm-2 img-div">
                        <img data-media="0"
                             src="<?php echo Url::to('@web/images/image.jpg', true); ?> "
                             class="package-image image responsive" id="img0" width="100" height="100"
                             alt="Package banner"/>
                        <?= $form->field($new_itinerary, 'media_id[]')->fileInput(['id' => 'media', 'class' => 'package-banner', 'data-media' => '0'])->label(false); ?>
                    </div>

                    <div class="col-sm-8">
                        <?= $form->field($new_itinerary, 'title[]')->textInput(['class' => 'form-control itinerary_title', 'value' => 'Day 1 - ']); ?>
                        <label class="control-label">Description</label>
                        <?= $form->field($new_itinerary, 'description[]')->textarea(['class' => 'note', 'id' => 'description0'])->label(false); ?>
                    </div>
                </div>

            </div>

        </div>
<?php } ?>

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Pricing Details</strong>
        </div>

        <div class="card-block">
            <div class="parent-form">
                <?php $count = count($model->packagePricings);
                if($count >0){
                for ($i = 0; $i < $count; $i++) {
                    $pm = $model->packagePricings[$i];?>
                    <div class="form-group row child-form">
                        <div class="col-sm-3">
                            <label class="control-label">Currency</label>
                            <?= $form->field($pm, 'currency_id[]')->dropDownList(Currency::getCurrency(), ['options' => [$pm->currency_id => ['Selected' => 'selected']]])->label(false); ?>
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">Price Type</label><br />
                            <?= Select2::widget([
                                'name' => 'PackagePricing[type][]',
                                'id' => "price_type$i",
                                'value' => isset($pm->type0->type)?$pm->type0->type:'', // initial value
                                'data' => PriceType::getPriceTypesNames(),
                                'maintainOrder' => true,
                                'options' => ['placeholder' => 'Select a Type', 'multiple' => false,'class' => 'price_type'],
                                'pluginOptions' => ['tags' => true],
                            ]); ?>
                            <p class="theme_hint help-block">&nbsp You can also add a new Price Type</p>
                            <p class='type-error error-hint help-block' style="display:none;">Price Type cannot be a number.</p>
                        </div>

                        <div class="col-sm-3">
                            <?= $form->field($pm, 'price[]')->textInput(['class' => ' package-price form-control', 'value' => $pm->price]) ?>
                            <div class="price_error help-block error-hint" style="display:none;">Price must be a
                                positive number.
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <br/>
                            <a class="fa fa-plus-circle fa-2x price-icon plus"></a>
                        </div>

                        <?php if ($i != 0) { ?>
                            <div class="col-sm-1">
                                <br/>
                                <a class="fa fa-minus-circle fa-2x price-icon minus"></a>
                            </div>
                        <?php } ?>

                    </div>
                <?php }
                }
                else{?>
                    <div class="form-group row child-form">
                        <div class="col-sm-3">
                            <label class="control-label">Currency</label>
                            <?= $form->field($new_price_model, 'currency_id[]')->dropDownList(Currency::getCurrency())->label(false); ?>
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
                            <p class="theme_hint help-block">&nbsp; You can also add a new Price Type</p>
                            <p class='type-error error-hint help-block' style="display:none;">Price Type cannot be a number.</p>
                        </div>

                        <div class="col-sm-3">
                            <?= $form->field($new_price_model, 'price[]')->textInput(['class' => 'package-price form-control']); ?>
                            <div class="price_error help-block error-hint" style="display:none;">Price must be a
                                positive number.
                            </div>
                        </div>

                        <div class="col-sm-1">
                            <br/>
                            <a class="fa fa-plus-circle fa-2x price-icon plus"></a>
                        </div>

                    </div>
                <?php }?>
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
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-round pull-right btn-sub']) ?>
                </div>
            </div>

            </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php
$this->registerJs('
 $("document").ready(function(){
 $(".from_validity_error").hide();
    $(".to_validity_error").hide();
/*var for_agent ="";
 for_agent = '.$model->for_agent.';
 if(for_agent == 0)
 {
  $("#check-label-customer").addClass("checked");
  $("#check-label-agent").removeClass("checked");
 }else
 {
 $("#check-label-agent").addClass("checked");
 $("#check-label-customer").removeClass("checked");
 }*/
   $(".dropdown-toggle").remove();
    $(".btn-codeview").remove();
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
 $("#for_customer").click(function(){
            if($(this).prop("checked") == true){
                $("#check-label-agent").removeClass("checked");
                $("#for_agent").attr("checked",false);

                $("#check-label-customer").addClass("checked");
            }
            else if($(this).prop("checked") == false){
                 $("#check-label-customer").removeClass("checked");
                $("#check-label-agent").addClass("checked");
                 $("#for_customer").attr("checked",false);
            }
        });

         $("#for_agent").click(function(){
            if($(this).prop("checked") == true){
                $("#check-label-agent").addClass("checked");
                $("#check-label-customer").removeClass("checked");
                $("#for_customer").attr("checked",false);
            }
            else if($(this).prop("checked") == false){
                $("#check-label-customer").addClass("checked");
                $("#check-label-agent").removeClass("checked");
                $("#for_agent").attr("checked",false);
            }
        });

$(".note").summernote();
var nights = $("#package-no_of_days_nights").val();
var no_of_days = $("#package_days").val();

      $("#package_days").html(+nights+1);


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
     // $("#media").css("opacity",0);


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

     $(".package_city").select2("destroy");
    $(".package_city").select2({
        tags: true,
        width: "134px",
    });
  //  $(".select2").

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

        var default_image= "' . Url::to("@web/images/image.jpg", true) . '";
          var image_al=[];

          for(j=0; j<= $("#package-no_of_days_nights").val();j++){

              image_al[j]=$(".package-image").eq(j).attr("src");

                }

       $("#package-no_of_days_nights").change(function(){
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

                      title[j]="Day "+(j+1)+" - ";
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

	  $(".minus").click(function(){
            $(".child-form").last().remove();
	    });

     $(".btn-update").click(function(){
            if($(this).text()=="Update"){
                $(this).text("View");
                $(".update-div").show();
                $(".view-div").hide();
            }
            else{
                 $(this).text("Update");
                 $(".update-div").hide();
                 $(".view-div").show();
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
'); ?>
