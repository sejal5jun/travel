<?php

use common\models\Inquiry;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FollowupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="followup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <!--<div class="col-md-3">
    <?php // $form->field($model, 'inquiry_package_id')->dropDownList(Inquiry::getQuotedInquires(), ['prompt' => 'Select Category', 'class' => 'form-control'])->label(false); ?>
</div>-->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <?php if (isset(Yii::$app->request->get('FollowupSearch')['date']))
                    $date = Yii::$app->request->get('FollowupSearch')['date'];?>
                <input type="text" name="FollowupSearch[date]" class="form-control" id="followupsearching"
                       data-provide="datepicker" data-date-format="M-dd-yyyy"
                       value="<?php if (isset($date)) {
                           if ($date != "") {
                               echo date('M-d-Y', strtotime($date));
                           } else {
                               echo date('M-d-Y');
                           }
                       } else {
                           echo date('M-d-Y');
                       }?>">

            </div>
        </div>

        <!--  <div class="col-md-3">
        <?php // $form->field($model, 'by')->textInput(['placeholder'=>'Quotation Manager'])->label(false) ?>
        </div>-->
        <div class="col-md-3">
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs('
$(document).ready(function(){


//$("#followupsearching").val("");

});


');


?>
