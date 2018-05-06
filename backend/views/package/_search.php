
<?php

use backend\models\enums\CategoryTypes;


use common\models\City;
use common\models\Country;
use common\models\Currency;
use common\models\PriceType;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PackageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

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
                'action' => ['index'],
                'method' => 'get',
            ]);

            ?>
            <div class="row">
                <div class="col-md-3 ">

                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' =>'pac_country',
                        'id' => 'country',
                        'value' => '', // initial value
                        'data' => Country::getCountries(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a Country', 'multiple' => true],
                        'pluginOptions' => ['tags' => false],
                    ]); ?>

                   </div>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'no_of_days_nights')->dropDownList(range(0,25),['prompt'=>'Select No of Nights'])->label(false) ?>
                </div>
                <div class="col-md-3 ">
                    <?= Select2::widget([
                        'model'=> $model,
                        'attribute' =>'pac_city',
                        'id' => 'package_city0',
                        'value' => '', // initial value
                        'data' => City::getCityId(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select a City', 'multiple' => true,'class' => 'package_city'],
                        'pluginOptions' => ['tags' => false],
                    ]); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'for_agent')->dropDownList(['0'=>'Customer','1'=>'Agent'],['prompt'=>'Package for'])->label(false) ?>

                    </div>

            </div>
            <div class="row">
                <div class="col-md-3 ">
                    <?= $form->field($model, 'name')->textInput(['class'=>'resetsearch form-control','placeholder'=>'Package Name'])->label(false) ?>
                </div>
                <div class="col-md-3 margin-bottom-5">
                    <?= $form->field($model, 'itinerary_name')->textInput(['class'=>'resetsearch form-control','placeholder'=>'Itinerary Name'])->label(false)?>

                </div>

                <div class="col-md-3 margin-bottom-5">
                    <?php //  $form->field($model, 'return_date')->textInput(['placeholder' => 'Return Date'])->label(false); ?>
                    <?= $form->field($model, 'category')->dropDownList(CategoryTypes::$headers,['prompt'=> 'Select Category'], [$model->category => ['Selected' => 'selected']])->label(false); ?>

                </div>


            </div>
          <!--  <div class="row">
                <div class="col-md-3 ">
                    <?/*= Select2::widget([
                        'model'=> $model,
                        'attribute' =>'pac_pricetype',
                        'id' => 'price_type0',
                        'value' => '', // initial value
                        'data' => PriceType::getPriceTypes(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select Price Type', 'multiple' => true,'class' => 'package_city'],
                        'pluginOptions' => ['tags' => false],
                    ]); */?>
                    <?php /*// $form->field($model, 'pac_pricetype')->dropDownList(PriceType::getPriceTypes(),['prompt'=> 'select Price Type'])->label(false); */?>
                </div>
                <div class="col-md-3 ">
                    <?/*= $form->field($model, 'pac_price')->textInput(['class' => 'package-price resetsearch form-control','placeholder'=>'Package Price'])->label(false); */?>
                </div>
                <div class="col-md-3 ">
                    <?/*= Select2::widget([
                        'model'=> $model,
                        'attribute' =>'pac_currency',
                        'id' => 'currency0',
                        'value' => '', // initial value
                        'data' => Currency::getCurrency(),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'Select Currency', 'multiple' => false,'class' => 'package_city'],
                        'pluginOptions' => ['tags' => false],
                    ]); */?>
                    <?php /*// $form->field($model, 'pac_currency')->dropDownList(Currency::getCurrency(),['prompt' =>'Select Currency'])->label(false); */?>
                </div>
            </div>-->

            <div class="row">
                <div class="col-sm-2 pull-right">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?>
                    <?= Html::Button('Reset', ['class' => 'btn btn-primary','id'=>'custom-reset']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>


<br/>
<?php
$this->registerJs('
$(document).ready(function(){
$("#Packagesearch").hide();

$("#Packagesearchbtn").click(function(){

$("#Packagesearch").slideToggle("hide");

});

$("#custom-reset").click(function(){

$(".resetsearch").val("");
$("#packagesearch-pac_country").select2("val","");
$("#packagesearch-pac_city").select2("val","");
$("#packagesearch-pac_pricetype").select2("val","");
$("#packagesearch-pac_pricetype").select2("val","");
$("#packagesearch-pac_currency").select2("val","");
$("select option:selected").removeAttr("selected");


});

});


');
?>







