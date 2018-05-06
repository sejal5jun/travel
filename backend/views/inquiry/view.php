<?php

use backend\models\enums\ActivityTypes;
use backend\models\enums\CategoryTypes;
use backend\models\enums\CustomerTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\PricingTypes;
use backend\models\enums\SourceTypes;
use common\models\Agent;
use common\models\City;
use common\models\Currency;
use common\models\RoomType;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Inquiry */
if($model->status==InquiryStatusTypes::COMPLETED)
    $this->title = $model->bookings[0]->booking_id;
else
    $this->title = 'KR-' . $model->inquiry_id;


if($type==InquiryStatusTypes::IN_QUOTATION)
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inquiries'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => $type])]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title"><?= InquiryStatusTypes::$headers[$model->status] ?> <?=$model->status!=InquiryStatusTypes::COMPLETED  ? "Inquiry" : ""?></div>
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
<?php
if ($model->status == InquiryStatusTypes::IN_QUOTATION || $model->status == InquiryStatusTypes::AMENDED) {
    ?>
    <div class="card-header">
        <?= Html::a(Yii::t('app', 'Update'), 'javascript:void(0);', ['class' => 'btn btn-primary btn-round btn-update',]); ?>
        <?= Html::a(Yii::t('app', 'Cancel'), '#', [
            'class' => 'btn btn-danger btn-round ',
            'data-toggle' => 'modal',
            'data-target' => '.notes-modal',

        ]);?>

             <?= Html::a(Yii::t('app', 'Move to Followups'), '#', [
                'class' => 'btn btn-round btn-primary pull-right',
                'data-toggle' => 'modal',
                'data-target' => '.bs-modal-sm-status'
            ]);
        ?>

        <?= Html::a(Yii::t('app', 'Send Quotation'), Yii::$app->getUrlManager()->createUrl(['/inquiry/add-quote', 'inquiry_id' => $model->id]), [
        	'class' => 'btn btn-primary btn-round pull-right'
    	]); ?>

        <?= Html::a(Yii::t('app', ($model->highly_interested == 1 ? 'Not Interested' : 'Highly Interested') ), '#', [
        	'class' => 'btn btn-primary btn-round pull-right btn-highly-interested',
        	'data-value' => ($model->highly_interested == 1 ? 0 : 1)
    	]); ?>

    </div>
<?php } ?>
    <div class="card-header">
        <?php if($model->status == InquiryStatusTypes::CANCELLED || $model->status == InquiryStatusTypes::COMPLETED) { ?>
            <?php echo "&nbsp;&nbsp;" . Html::a(Yii::t('app', 'Change Status'), '#', [
                   'class' => 'btn btn-round btn-primary',
                   'data-toggle' => 'modal',
                   'data-target' => '.bs-modal-sm-change-status'
               ]); ?>
        <?php } ?>
        <?php if ($model->status == InquiryStatusTypes::COMPLETED){ ?>
            <?php  echo "&nbsp;&nbsp;" .  Html::a(Yii::t('app', 'Update Inquiry'), '#', [
                    'class' => 'btn btn-round btn-primary',
                    'data-toggle' => 'modal',
                    'data-target' => '.inquiry-modal-sm'
                ]); ?>
        <?php } ?>
    </div>
<?php if ($model->status != InquiryStatusTypes::VOUCHERED && $model->status != InquiryStatusTypes::COMPLETED  || $activity == ActivityTypes::ADDED || $activity == ActivityTypes::UPDATED) {  ?>
    <div class="view-div">

        <?php  if($model->status==InquiryStatusTypes::AMENDED || $model->status==InquiryStatusTypes::CANCELLED){ ?>
            <div class="card-header bg-danger-dark">
                <strong class="text-white">Follow Up Details</strong>
            </div>
            <div class="card-block">
                <?php $f_count = count($followup_model);
                for($i=0;$i<$f_count;$i++){?>
                    <div class="form-group row">
                        <?php if($followup_model[$i]->by!=''){ ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Head:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $followup_model[$i]->by0->username  ?></label>
                        </div>
                        <?php } ?>
                        <?php if($followup_model[$i]->date!=''){ ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Date:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= date('M-d-Y',$followup_model[$i]->date) ?></label>
                        </div>
                        <?php } ?>
                        <?php if($i==$f_count-1){ ?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Notes:</strong>
                                </label><br/>
                                <label class="control-label model-view"><?= "First Follow Up" ?></label>
                            </div>
                        <?php } else if($followup_model[$i]->note!=''){ ?>
                        <div class="col-sm-6">
                            <label class="control-label model-view-label">
                                <strong>Notes:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $followup_model[$i]->note ?></label>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
    <?php  }?>
        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Passenger Details</strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-4 ">
                    <label class="control-label model-view-label">
                        <strong>Passenger Name:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->name ?></label>
                </div>

                <div class="col-sm-4">
                    <label class="control-label model-view-label">
                        <strong>Passenger Email:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->email ?></label>
                </div>

                <div class="col-sm-4">
                    <label class="control-label model-view-label">
                        <strong>Passenger Mobile:</strong>
                    </label><br/>
                    <label class="control-label model-view"><a href="tel:<?= $model->mobile ?>"><?= $model->mobile ?></a></label>
                </div>
            </div>
        </div>

        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Travel Details</strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>From Date:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($model->from_date) ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Return Date:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($model->return_date); ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong><?= $model->no_of_days; ?> Nights</strong>
                    </label><br/>
                    <label class="control-label model-view"></label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Destination:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->destination ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Leaving From:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->leaving_from ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Room Type:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $roomtypes; ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>No.Of Rooms:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->room_count ?></label>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Adult Count:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->adult_count ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Children Count:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->children_count ?></label>
                </div>
            </div>
            <div class="form-group row">
                <?php if (count($model->inquiryChildAges) > 0) {
                    for ($i = 0; $i < $model->children_count; $i++) {
                        $child_age_model = $model->inquiryChildAges[$i];?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Child <?= $i + 1 ?> Age:</strong>
                            </label>
                            <label class="control-label model-view"><?= $child_age_model->age . ' yrs' ?></label>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>

        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Inquiry Details</strong>
            <strong class="text-white pull-right"><?= InquiryTypes::$headers[$model->type] ?></strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Customer Type:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->customer_type != '' ? CustomerTypes::$headers[$model->customer_type] : '' ?></label>
                </div>
                <?php if ($model->agent_id != '') { ?>
                    <div class="col-sm-2">
                        <label class="control-label model-view-label">
                            <strong>Agent:</strong>
                        </label><br/>
                        <label class="control-label model-view"><?= $model->agent->name ?></label>
                    </div>
                <?php } ?>
                <div class="col-sm-2">
                    <label class="control-label model-view-label">
                        <strong>Source:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= SourceTypes::$headers[$model->source] ?></label>
                </div>
                <?php if($model->source==SourceTypes::REFERENCE){?>
                    <div class="col-sm-2">
                        <label class="control-label model-view-label">
                            <strong>Reference:</strong>
                        </label><br/>
                        <label class="control-label model-view"><?= $model->reference ?></label>
                    </div>
                <?php } ?>

            </div>


            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Inquiry Head:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= $model->inquiry_head != '' ? $model->inquiryHead->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label model-view-label">
                        <strong>Quotation Manager:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= $model->quotation_manager != '' ? $model->quotationManager->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label model-view-label">
                        <strong>Quotation Staff:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= $model->quotation_staff != '' ? $model->quotationStaff->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label model-view-label">
                        <strong>Follow Up Manager:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= $model->follow_up_head != '' ? $model->followUpHead->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-2">
                    <label class="control-label model-view-label">
                        <strong>Follow Up Staff:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= $model->follow_up_staff != '' ? $model->followUpStaff->username : 'Not Set' ?></label>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label model-view-label">
                    <strong>Inquiry Details:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $model->inquiry_details ?></label>
            </div>
        </div>

    </div>
<?php }
else if($activity==''){
?>
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Booking Details: <?= $model->bookings[0]->booking_id ?></strong>
    </div>
    <div class="card-block">
        <div class="form-group row">
            <div class="col-sm-4 ">
                <label class="control-label model-view-label">
                    <strong>Final Price:</strong>
                </label><br/>
                <?php if($model->status == InquiryStatusTypes::COMPLETED) {?>
                <label class="control-label model-view"><?= isset($model->bookings[0]->currency_id) ? $model->bookings[0]->currency->name . " " . $model->bookings[0]->final_price : '' ?></label>
                    <?php }else { ?>
                    <label class="control-label model-view"><?= isset($model->bookings[0]->voucher_currency_id) ? Currency::find()->where(['id'=>$model->bookings[0]->voucher_currency_id])->one()->name . " " . $model->bookings[0]->voucher_final_price : '' ?></label>
                <?php } ?>

            </div>

            <div class="col-sm-4">
                <label class="control-label model-view-label">
                    <strong>Booking Date:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= date("M-d-Y", $model->bookings[0]->inquiryPackage->from_date); ?></label>
            </div>
        </div>
    </div>
        <div class="card-header bg-danger-dark">
            <strong class="text-white">Follow Up Details</strong>
        </div>

        <div class="card-block">
            <?php foreach($followup_model as $f){?>
                <div class="form-group row">
                    <?php if($f->by!=''){ ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Head:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $f->by0->username  ?></label>
                        </div>
                    <?php } ?>
                    <?php if($f->date!=''){ ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Date:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= date('M-d-Y',$f->date) ?></label>
                        </div>
                    <?php } ?>
                    <?php if($f->note!=''){ ?>
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
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Passenger Details</strong>
    </div>

    <div class="card-block  update-part">
        <div class="form-group row">
            <?php $form = ActiveForm::begin(); ?>

                <div class="col-sm-4">
                    <label class="control-label model-view-label">
                        <strong>Passenger Name:</strong>
                    </label>
                    <?= $form->field($new_inq_package_model, 'passenger_name')->textInput()->label(false) ?>
                </div>


                <div class="col-sm-4">
                    <label class="control-label model-view-label">
                        <strong>Passenger Email:</strong>
                    </label>
                    <?= $form->field($new_inq_package_model, 'passenger_email')->textInput()->label(false) ?>
                </div>

                <div class="col-sm-4">
                    <label class="control-label model-view-label">
                        <strong>Passenger Mobile:</strong>
                    </label>
                    <?= $form->field($new_inq_package_model, 'passenger_mobile')->textInput()->label(false) ?>
                </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <?= Html::button('View',['class'=>'btn btn-round btn-primary pull-right','id'=>'view-btn']);    ?>
                    <?= Html::submitButton( 'Update', ['class' => 'btn btn-primary  btn-round pull-right']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="card-block  view-part">
        <div class="form-group row">
            <div class="col-sm-4 ">
                <label class="control-label model-view-label">
                    <strong>Passenger Name:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->passenger_name ?></label>
            </div>

            <div class="col-sm-4">
                <label class="control-label model-view-label">
                    <strong>Passenger Email:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->passenger_email ?></label>
            </div>

            <div class="col-sm-4">
                <label class="control-label model-view-label">
                    <strong>Passenger Mobile:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->passenger_mobile ?></label>
            </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">

                    <?= Html::button('Update',['class'=>'btn btn-round btn-primary pull-right','id'=>'update-btn'])?>
                </div>
            </div>

    </div>

    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Travel Details</strong>
    </div>
    <div class="card-block">
        <div class="form-group row">

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>From Date:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= Yii::$app->formatter->asDate($model->bookings[0]->inquiryPackage->from_date) ?></label>
            </div>

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Return Date:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= Yii::$app->formatter->asDate($model->bookings[0]->inquiryPackage->return_date); ?></label>
            </div>

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong><?= $model->bookings[0]->inquiryPackage->no_of_nights; ?> Nights</strong>
                </label><br/>
                <label class="control-label model-view"></label>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Destination:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->destination ?></label>
            </div>

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Leaving From:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->leaving_from ?></label>
            </div>
            <?php
            $roomtypes = '';
            if (isset($model->bookings[0]->inquiryPackage->inquiryPackageRoomTypes)) {
                foreach ($model->bookings[0]->inquiryPackage->inquiryPackageRoomTypes as $room) {
                    $roomtypes .= $room->roomType->type;
                    $roomtypes .= ',';
                }
                $roomtypes = rtrim($roomtypes, ',');
            }
            ?>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Room Type:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $roomtypes; ?></label>
            </div>

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>No.Of Rooms:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->room_count ?></label>
            </div>

        </div>

        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Adult Count:</strong>
                </label><br/>
                <label class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->adult_count ?></label>
            </div>

            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Children Count:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->children_count ?></label>
            </div>
        </div>
        <div class="form-group row">
            <?php if (count($model->bookings[0]->inquiryPackage->inquiryPackageChildAges) > 0) {
                for ($i = 0; $i < $model->bookings[0]->inquiryPackage->children_count; $i++) {
                    $child_age = $model->bookings[0]->inquiryPackage->inquiryPackageChildAges[$i];?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Child <?= $i + 1 ?> Age:</strong>
                        </label>
                        <label class="control-label model-view"><?= $child_age->age . ' yrs' ?></label>
                    </div>
                <?php }
            } ?>
        </div>
    </div>

    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Inquiry Details</strong>
        <strong class="text-white pull-right"><?= InquiryTypes::$headers[$model->type] ?></strong>
    </div>
    <div class="card-block">
        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Customer Type:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->customer_type != '' ? CustomerTypes::$headers[$model->customer_type] : '' ?></label>
            </div>
            <?php if ($model->agent_id != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Agent:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->agent->name ?></label>
                </div>
            <?php } ?>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Source:</strong>
                </label><br/>
                <label class="control-label model-view"><?= SourceTypes::$headers[$model->source] ?></label>
            </div>
            <?php if($model->source==SourceTypes::REFERENCE){?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Reference:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->reference ?></label>
                </div>
           <?php } ?>
        </div>

        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Inquiry Head:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->inquiry_head != '' ? $model->inquiryHead->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-2">
                <label class="control-label model-view-label">
                    <strong>Quotation Manager:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->quotation_manager != '' ? $model->quotationManager->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-2">
                <label class="control-label model-view-label">
                    <strong>Quotation Staff:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->quotation_staff != '' ? $model->quotationStaff->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-2">
                <label class="control-label model-view-label">
                    <strong>Follow Up Manager:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->follow_up_head != '' ? $model->followUpHead->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-2">
                <label class="control-label model-view-label">
                    <strong>Follow Up Staff:</strong>
                </label><br/>
                <label
                    class="control-label model-view"><?= $model->follow_up_staff != '' ? $model->followUpStaff->username : 'Not Set' ?></label>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label model-view-label">
                <strong>Inquiry Details:</strong>
            </label><br/>
            <label class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->inquiry_details ?></label>
        </div>
    </div>

    <?php if ($model->bookings[0]->inquiryPackage->is_quotation_sent != 0 && $model->bookings[0]->inquiryPackage->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Package Details</strong>
            <strong
                class="text-white pull-right"><?= $model->bookings[0]->inquiryPackage->package->category != '' ? CategoryTypes::$headers[$model->bookings[0]->inquiryPackage->package->category] : '' ?></strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-12">
                    <h3 class="text-center">
                        <?= isset($model->bookings[0]->inquiryPackage->package_name) ? $model->bookings[0]->inquiryPackage->package_name : '' ?>
                    </h3>
                    <h5 class="text-center"><?php $nights = isset($model->bookings[0]->inquiryPackage->no_of_days_nights) ? $model->bookings[0]->inquiryPackage->no_of_days_nights : '' ?>
                        <?= isset($nights) ? $nights : '' ?> Nights / <?= isset($nights) ? $nights + 1 : '' ?> Days
                    </h5>
                </div>
            </div>
        </div>

        <?php if ($model->type == InquiryTypes::PACKAGE_WITH_ITINERARY) { ?>

            <div class="card-header view-header bg-danger-dark">
                <strong class="text-white">Itinerary Details</strong>
                <strong class="text-white pull-right"><?= isset($model->bookings[0]->inquiryPackage->quotationItineraries) ? $model->bookings[0]->inquiryPackage->quotationItineraries[0]->no_of_itineraries . ' Nights' : '' ?></strong>
            </div>
            <div class="card-block">
                <?php $count = count($model->bookings[0]->inquiryPackage->quotationItineraries);

                for ($i = 0; $i < $count; $i++) {
                    $it = $model->bookings[0]->inquiryPackage->quotationItineraries[$i];?>
                    <div class="form-group row itinerary-child">
                        <div class="col-sm-2 img-div">
                            <img
                                src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($model->bookings[0]->inquiryPackage->package->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                                class="package-image image responsive" width="100" height="100"
                                alt="Package banner"/>
                        </div>

                        <div class="col-sm-8">
                            <label class="control-label model-view-label">
                                <strong><?= $it->title ?></strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $it->description ?></label>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Pricing Details</strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <?php $count = count($model->bookings[0]->inquiryPackage->quotationPricings);
                for ($i = 0; $i < $count; $i++) {
                    $pm = $model->bookings[0]->inquiryPackage->quotationPricings[$i];?>

                    <div class="col-sm-3">
                        <label class="control-label model-view">
                            <?= PricingTypes::$headers[$pm->type] ?><br/>
                            <strong><?= $pm->currency->name ?><?= ' ' . $pm->price ?></strong>
                        </label>
                    </div>

                <?php } ?>
            </div>
            <div class="form-group row">
                <?php if ($model->bookings[0]->inquiryPackage->terms_and_conditions != '') { ?>
                    <div class="col-sm-6">
                        <label class="control-label model-view-label">
                            <strong>Terms And Conditions:</strong>
                        </label><br/>
                        <label
                            class="control-label model-view"><?= isset($model->bookings[0]->inquiryPackage->terms_and_conditions) ? $model->bookings[0]->inquiryPackage->terms_and_conditions : '' ?></label>
                    </div>
                <?php } ?>
            </div>
        </div>
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Other Details</strong>
    </div>
    <div class="card-block">

            <div class="form-group row">
                <?php if ($model->bookings[0]->inquiryPackage->package_include != '') { ?>
                    <div class="col-sm-6">
                        <label class="control-label model-view-label">
                            <strong>Package Includes:</strong>
                        </label><br/>
                        <label
                            class="control-label model-view"><?= isset($model->bookings[0]->inquiryPackage->package_include) ? $model->bookings[0]->inquiryPackage->package_include : '' ?></label>
                    </div>
                <?php } ?>
                <?php if ($model->bookings[0]->inquiryPackage->package_exclude != '') { ?>
                    <div class="col-sm-6">
                        <label class="control-label model-view-label">
                            <strong>Package Excludes:</strong>
                        </label><br/>
                        <label
                            class="control-label model-view"><?= isset($model->bookings[0]->inquiryPackage->package_exclude) ? $model->bookings[0]->inquiryPackage->package_exclude : '' ?></label>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group row">
                <?php if ($model->bookings[0]->inquiryPackage->other_info != '') { ?>
                    <div class="col-sm-6">
                        <label class="control-label model-view-label">
                            <strong>Other Information:</strong>
                        </label><br/>
                        <label
                            class="control-label model-view"><?= isset($model->bookings[0]->inquiryPackage->other_info) ? $model->bookings[0]->inquiryPackage->other_info : '' ?></label>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="card-header view-header bg-danger-dark">
                <strong class="text-white">Hotel Details</strong>
            </div>
            <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view-label">
                            <strong>Hotel Details:</strong>
                        </label><br/>
                        <label
                            class="control-label model-view"><?= $model->bookings[0]->inquiryPackage->hotel_details ?></label>
                    </div>
                </div>
            </div>
        <?php } ?>

    <?php } ?>
    <div class="update-div" style="display: none">
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
                        <?= $form->field($model, 'leaving_from')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Room Count</label>
                        <?= $form->field($model, 'room_count')->dropDownList(range(0,12))->label(false) ?>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Room Type</label>
                        <?= Select2::widget([
                            'name' => 'room_type',
                            'id' => 'room',
                            'value' => $room_arr, // initial value
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
                        <?= $form->field($model, 'adult_count')->dropDownList(range(0,12))->label(false) ?>
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
                            <a class="fa fa-plus-circle fa-2x" data-toggle="modal" data-target=".agent-modal"></a>
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
                        <?= $form->field($model, 'source')->dropDownList(SourceTypes::$headers, ['options' => [$model->source => ['Selected' => 'selected']]])->label(false); ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'highly_interested')->checkbox(['options' => [$model->source => ['Selected' => 'selected']]])->label(false); ?>
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
                        <?= $form->field($model, 'inquiry_details')->textarea(['class' => 'summernote']) ?>
                    </div>
                </div>
                <div class="form-group row">
                    <?= Html::submitButton($model->isNewRecord ? 'Add New Inquiry' : 'Update Inquiry', ['class' => 'btn btn-primary btn-lg btn-round pull-right', 'id' => 'add_inquiry']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


    </div>
    </div>

<div class="modal bs-modal-sm-change-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change Status Of Inquiry</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group form-material">

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="control-label">Status</label>
                            <?= $form->field($new_inquiry_model, 'status')->dropDownList(InquiryStatusTypes::$can_status, ['id' => 'booking_status', 'prompt' => 'Select Status'])->label(false); ?>
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="price form-group row" style="display: none;">
                            <div class="col-sm-3">
                                <?= $form->field($new_booking_model, 'voucher_currency_id')->dropDownList(Currency::getCurrency()); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($new_booking_model, 'voucher_inr_rate')->textInput(['value' => 1]); ?>
                                <div id="rate_error" class="help-block error-hint" style="display: none">Rate cannot be blank.</div>

                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($new_booking_model, 'voucher_final_price')->textInput(); ?>
                                <div id="price_error" class="help-block error-hint" style="display: none">Final Price cannot be blank.</div>
                            </div>
                      </div>
                        <div class="col-sm-12">
                            <label class="control-label">Notes</label>
                            <?= $form->field($new_inquiry_model, 'notes')->textarea(['rows' => 6, 'class' => 'summernote','id'=>'status-notes'])->label(false) ?>
                            <div id="notes_error2" class="help-block error-hint" style="display: none">Notes cannot be blank.</div>
                        </div>
                    </div>
                    <?php echo Html::hiddenInput('inquiry_id', $model->id, ['id' => 'inquiry_id']); ?>

                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right', 'id' => 'status-btn']) ?>

                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>




<div class="modal bs-modal-sm agent-modal" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Agent</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
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
                                <p class="theme_hint help-block">&nbsp You can also add a new City</p>

                                <div class='error error-hint help-block' style="display:none;">City cannot be a
                                    number.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Add', ['class' => 'btn btn-success pull-right', 'id' => 'add_agent']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

    <div class="modal notes-modal" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cancel Inquiry</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['delete','id'=>$model->id],
                        'method' => 'post',
                    ]); ?>
                    <div class="form-group form-material row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <?= $form->field($model, 'notes')->textarea(['class'=>'summernote', 'value' => '']) ?>
                                    <div id="notes_error" class="help-block error-hint" style="display: none">Notes cannot be blank.</div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer no-border">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?= Html::submitButton('Cancel', ['class' => 'btn btn-danger pull-right /*swal-warning-inquiry-cancel*/', 'id' => 'notes']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<div class="modal bs-modal-sm-status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog add-quote-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Quotation Details</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group form-material">
                    <div class="form-group row">
                        <?php if($model->type==InquiryTypes::PER_ROOM_PER_NIGHT){?>
                            <div class="col-sm-12">
                                <label class="control-label">Hotel Details</label>
                                <?= $form->field($inquiry_quotation, 'hotel_details')->textarea(['rows' => 10, 'class' => 'summernote'])->label(false) ?>
                                <div id="hotel_error" class="help-block error-hint" style="display: none">Hotel details cannot be blank.</div>
                            </div>
                        <?php } else{?>
                            <div class="col-sm-12">
                                <label class="control-label">Quotation Details</label>
                                <?= $form->field($inquiry_quotation, 'quotation_details')->textarea(['rows' => 10, 'class' => 'summernote'])->label(false) ?>
                                <div id="quotation_error" class="help-block error-hint" style="display: none">Quotation details cannot be blank.</div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right', 'id' => 'update_status']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal inquiry-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Update Inquiry</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group form-material">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'email')->textInput() ?>
                        </div>

                        <div class="col-sm-4">
                            <?= $form->field($model, 'mobile')->textInput() ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="control-label">Inquiry Head</label>
                            <?= $form->field($model, 'inquiry_head')->dropDownList(User::getHead(), ['options' => [Yii::$app->user->identity->id =>['Selected' => 'selected']]])->label(false); ?>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Quotation Manager</label>
                            <?= $form->field($model, 'quotation_manager')->dropDownList(User::getHead(), ['options' => [$model->quotation_manager => ['Selected' => 'selected']]])->label(false); ?>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Quotation Staff</label>
                            <?= $form->field($model, 'quotation_staff')->dropDownList(User::getHead(), ['options' => [$model->quotation_staff => ['Selected' => 'selected']]])->label(false); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="control-label">Follow Up Head</label>
                            <?= $form->field($model, 'follow_up_head')->dropDownList(User::getHead(), ['options' => [$model->follow_up_head => ['Selected' => 'selected']]])->label(false); ?>
                        </div>

                        <div class="col-sm-4">
                            <label class="control-label">Follow Up Staff</label>
                            <?= $form->field($model, 'follow_up_staff')->dropDownList(User::getHead(), ['options' => [$model->follow_up_staff => ['Selected' => 'selected']]])->label(false); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
/*this->registerJs('


		 $("#status").change(function(){


    var status=$("#status").children("option").filter(":selected").val();

    if (status == "'.InquiryStatusTypes::QUOTED.'")
    {
        $("#followupdate").show();
    }else
    {
        $("#followupdate").hide();
    }

     if (status == "'.InquiryStatusTypes::COMPLETED.'")
    {
        $(".price").show();
    }else
    {
        $(".price").hide();
    }
    });

$("#tm").clockpicker({
        autoclose: true,
    });

 ');*/
 
?>

<?php

$this->registerJs('
 $(document).ready(function(){
$(".update-part").hide();
var type = '.$model->type.';
		//alert(type);

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

 /*$("#inquiry-follow_up_head").change(function(){
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


$("#booking_status").change(function(){
var status = $(this).val();
if(status == '.InquiryStatusTypes::VOUCHERED.')
{
$(".price").show();
}else
{
$(".price").hide();
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

           $("#update-btn").click(function(){

$(".view-part").hide();
$(".update-part").show();


});
$("#view-btn").click(function(){

$(".view-part").show();
$(".update-part").hide();

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

       $("#add_inquiry").click(function(){
            var org_str= $(".summernote").val();
            var desc = org_str.replace(/font-family:/g, "");
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
            $(".summernote").summernote("code", desc);
       });

       $("#notes").on("click", function (e) {
            var notes =$("#inquiry-notes").val();
         
           if(notes==""){
                $("#notes_error").show();
                 return false;
           }
           else{
                $("#notes_error").hide();
                  return true;
           }
       });
       $("#status-btn").on("click", function (e) {
            var notes =$("#status-notes").val();
             var status = $("#booking_status").val();

             if(status == '.InquiryStatusTypes::VOUCHERED.')
             {
                var inr_rate = $("#booking-voucher_inr_rate").val();
             var final_price = $("#booking-voucher_final_price").val();
             if(inr_rate==""){
                $("#rate_error").show();
                 return false;
           }
           if(final_price==""){
                $("#price_error").show();
                 return false;
           }

             }
           if(notes==""){
                $("#notes_error2").show();
                 return false;
           }
           else{
                $("#notes_error2").hide();
                  return true;
           }
       });
	   
	   
	 
           $("#update_status").on("click", function (e) {
            if(type == '.InquiryTypes::PER_ROOM_PER_NIGHT.'){
                 var hotel_details = $("#inquirypackage-hotel_details").val();
                var inc = hotel_details.replace(/font-family:/g, "");
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
             $("#inquirypackage-hotel_details").summernote("code", inc);
              
               if(hotel_details==""){
                    $("#hotel_error").show();
                     return false;
               }
               else{
                    $("#hotel_error").hide();
                      return true;
               }
            }
            else{
                    var quotation_details = $("#inquirypackage-quotation_details").val();
                    var inc = quotation_details.replace(/font-family:/g, "");
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
                 $("#quotation_details-hotel_details").summernote("code", inc);
                   if(quotation_details==""){
                        $("#quotation_error").show();
                         return false;
                   }
                   else{
                        $("#quotation_error").hide();
                          return true;
                   }
            }

        });
             

       
	   
	   
	
	
			var source = $("#inquiry-source").val();
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


		$(".btn-highly-interested").on("click", function() {
	        var $this = $(this);
	        $.ajax({
	            url: "' . Yii::$app->urlManager->createAbsoluteUrl("inquiry/mark-highly-interested") . '",
	            method: "GET",
	            data: {
	                id: "' . $model->id . '",
	                highly_interested: $this.data("value")
	            },
	            success: function(data) {
	                if (data == "success") {
	                    if ($this.data("value") == 1) {
	                        $this.data("value", 0);
	                        $this.html("Not Interested");
	                    } else {
	                        $this.data("value", 1);
	                        $this.html("Highly Interested");
	                    }
	                }
	            },
	            error: function(data) {

	            }
	        });
	    });
	
	});
');
?>
