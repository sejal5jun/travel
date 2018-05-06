<?php

use common\models\City;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="card bg-white">

        <div class="card-header bg-danger-dark">
            <strong class="text-white">Agent Details</strong>
        </div>

        <div class="card-block">
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-group row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'name')->textInput() ?>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">City</label>
                    <?= Select2::widget([
                        'name' => 'Agent[city_id]',
                        'id' => 'city',
                        'value' => $city_name, // initial value
                        'data' => City::getCity(),
                        'options' => ['placeholder' => 'Select City'],
                        'pluginOptions' => ['tags' => true,],
                    ]); ?>
                    <p class="theme_hint help-block">&nbsp You can also add a new City</p>

                    <div class='error error-hint help-block' style="display:none;">City cannot be a number.</div>
                </div>
            </div>

            <div class="form-group row">
                <?= Html::submitButton($model->isNewRecord ? 'Add New Agent' : 'Update Agent', ['class' => 'btn btn-primary btn-round pull-right btn-lg']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
<?php

$this->registerJs('
    $(document).ready(function(){

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





	});

');