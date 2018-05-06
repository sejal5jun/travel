<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 04-07-2016
 * Time: 13:21
 */
/* @var $package_model common\models\Package */
/* @var $itinerary common\models\Itinerary */
/* @var $price_model common\models\PackagePricing */

use backend\models\enums\DirectoryTypes;
use common\models\City;
use common\models\Country;
use common\models\Currency;
use common\models\PriceType;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]); ?>
<?php $country=[];

foreach($package_model->packageCountries as $val)
{
    $country = $val->country_id;

}

?>
<div class="form-group row">
    <div class="col-sm-6">
        <label class="control-label">Country</label><br>

        <?= Select2::widget([
            'name' => 'package_country',
            'id' => 'country',
            'value' => $country, // initial value
            'data' => Country::getCountries(),
            'maintainOrder' => true,
            'options' => ['placeholder' => 'Select a Country', 'multiple' => true, 'class' => 'package_country'],
            'pluginOptions' => ['tags' => false],
        ]); ?>
    </div>
    <div class="col-sm-offset-1 col-sm-2  custom-display">
        <div class="custom-width">
            <label class="control-label">Nights</label>
            <?= $form->field($package_model, 'no_of_days_nights')->dropDownList(range(0, 25),['class' => 'p_nights form-control'])->label(false) ?>
        </div>
        <div class="custom-width">
            <label class="control-label">Days</label><br/>
            <label id="package_days" class="control-label"><?php $days = $package_model->no_of_days_nights;$days=$days+1;echo $days;?></label>
        </div>
    </div>

</div>
<?php
$city=[];
$city_nights=[];
foreach($package_model->packageCities as $val)
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
} else {?>

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
    <div class="col-sm-6">
        <?= $form->field($package_model, 'itinerary_name')->textInput()?>
    </div>
    <div class="col-sm-4">
        <label class="control-label">Package Name</label>
        <?= $form->field($package_model, 'name')->textInput()->label(false) ?>
        <div id="package_error" class="help-block error-hint" style="display: none">Package cannot be blank.</div>
    </div>


</div>


<?php if($itinerary_flag == "true" && count($itinerary)>0){?>
<div class="itinerary-parent">

    <?php $count = count($itinerary);

        for($i=0;$i<$count;$i++){
            $it = $itinerary[$i];?>
            <div class="form-group row itinerary-child">
                <div class="col-sm-2 img-div">
                    <img
                        src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($package_model->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                        class="package-image image responsive" width="100" height="100"
                        alt="Package banner" />
                   <?=$form->field($it,  "media_id[$i]")->fileInput(['class' => 'package-banner'])->label(false); ?>
                </div>

                <div class="col-sm-8">
                    <?= $form->field($it, 'title[]')->textInput(['class' => 'itinerary_title form-control', 'value' => $it->title]) ?>
                    <label class="control-label">Description</label>
                    <?= $form->field($it, 'description[]')->textarea(['class' => 'note','value' => $it->description, 'id' => "description$i"])->label(false) ?>
                </div>

            </div>
        <?php } ?>
</div>
<?php }?>
<div class="parent-form">
    <?php $count = count($price_model);
        for($i=0;$i<$count;$i++){
            $pm = $price_model[$i];?>
            <div class="form-group row child-form">
                <div class="col-sm-3">
                    <label class="control-label">Currency</label>
                    <?= $form->field($pm, 'currency_id[]')->dropDownList(Currency::getCurrency(),['options' => [$pm->currency_id=> ['Selected' => 'selected']]])->label(false); ?>
                </div>

                <div class="col-sm-3">
                   <!-- <div class="col-sm-3">
                        <label class="control-label">Price Type</label><br />
                        <?/*= Select2::widget([
                            'name' => 'PackagePricing[type][]',
                            'id' => "price_type",
                            'value' =>'',// $pm->type0->type, // initial value
                            'data' => PriceType::getPriceTypesNames(),
                            'maintainOrder' => true,
                            'options' => ['placeholder' => 'Select a Type', 'multiple' => false,'class' => 'price_type'],
                            'pluginOptions' => ['tags' => true],
                        ]); */?>
                        <p class="theme_hint help-block">&nbsp You can also add a new Price Type</p>
                        <p class='type-error error-hint help-block' style="display:none;">Price Type cannot be a number.</p>
                    </div>-->
                    <label class="control-label">Price Type</label>
                    <?= $form->field($pm, 'type[]')->dropDownList(PriceType::getPriceTypesNames(),['options' => [$pm->type0->type=> ['Selected' => 'selected']],'class' => 'price_type', 'id' => "price_type$i"])->label(false); ?>
                    <p class="theme_hint help-block">&nbsp You can also add a new Price Type</p>
                </div>

                <div class="col-sm-3">
                    <?= $form->field($pm, 'price[]')->textInput(['class' => ' package-price form-control','value' => $pm->price]) ?>
                    <div class="price_error help-block error-hint" style="display:none;">Price must be a
                        positive number.
                    </div>
                </div>

                <div class="col-sm-1">
                    <br/>
                    <a class="fa fa-plus-circle fa-2x price-icon plus"></a>
                </div>

                <?php if($i!=0){?>
                    <div class="col-sm-1">
                        <br/>
                        <a class="fa fa-minus-circle fa-2x price-icon minus"></a>
                    </div>
                <?php } ?>

            </div>


        <?php } ?>
</div>


    <div class="form-group row">
        <div class="col-sm-12">
            <label class="control-label">Pricing Details</label>
            <?= $form->field($package_model, 'pricing_details')->textarea(['class' => 'summernote'])->label(false) ?>
        </div>
    </div>

<div class="form-group row">
    <div class="col-sm-12">
        <label class="control-label">Terms And Conditions</label>
        <?= $form->field($package_model, 'terms_and_conditions')->textarea(['class' => 'summernote'])->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <label class="control-label">Package Includes</label>
        <?= $form->field($package_model, 'package_include')->textarea(['class' => 'summernote'])->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <label class="control-label">Package Excludes</label>
        <?= $form->field($package_model, 'package_exclude')->textarea(['class' => 'summernote'])->label(false) ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12">
        <label class="control-label">Additional Details</label>
        <?= $form->field($package_model, 'other_info')->textarea(['class' => 'summernote'])->label(false) ?>
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
           <br /> Please find below your package details as required.
         </textarea>
    </div>
    </div>

<div class="form-group row">
    <div class=pull-right>
        <?= Html::submitButton('Send Quotation', ['class' => 'btn btn-primary btn-round', 'id' => 'add_quotation']) ?>
    </div>
</div>

