<?php

use backend\models\enums\CustomerTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;
use backend\models\enums\UserTypes;
use common\models\Currency;
use common\models\InquiryPackage;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Inquiry */

$this->title = 'KR-' . $model->inquiry_id;
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inquiries'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED])]];*/
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="page-title">
        <div class="title"><?= InquiryStatusTypes::$headers[$model->status] ?> Inquiry</div>
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
        } ?>
    </ol>
    <div class="card bg-white">
        <div class="card-header">
            <?php //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF){?>
                <?php
                echo Html::a(Yii::t('app', 'Cancel'), '#', [
                    'class' => 'btn btn-danger btn-round pull-right',
                    'data-toggle' => 'modal',
                    'data-target' => '.notes-modal'
                    /*'data' => [
                        'url' => Yii::$app->getUrlManager()->createUrl(['/inquiry/delete', 'id' => $model->id])
                    ],*/
                ]);
           // }
            //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER || Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF) {
                echo Html::a(Yii::t('app', 'Send Quotation'), Yii::$app->getUrlManager()->createUrl(['/inquiry/add-quote', 'inquiry_id' => $model->id]), ['class' => 'btn btn-primary btn-round',]);
           // }
           // if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF || Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER || Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF){
                echo "&nbsp;&nbsp;" .  Html::a(Yii::t('app', 'Change Status'), '#', [
                        'class' => 'btn btn-round btn-primary',
                        'data-toggle' => 'modal',
                        'data-target' => '.bs-modal-sm'
                    ]);

                echo "&nbsp;&nbsp;" .  Html::a(Yii::t('app', 'Update Inquiry'), '#', [
                        'class' => 'btn btn-round btn-primary',
                        'data-toggle' => 'modal',
                        'data-target' => '.inquiry-modal-sm'
                    ]);

                echo "&nbsp;&nbsp;" . Html::a(Yii::t('app', 'Schedule Followup'), '#', [
                        'class' => 'btn btn-round btn-primary',
                        'data-toggle' => 'modal',
                        'data-target' => '.schedule-followup-modal'
                    ]);

                echo "&nbsp;&nbsp;" . Html::a(Yii::t('app', ($model->highly_interested == 1 ? 'Not Interested' : 'Highly Interested') ), '#', [
                        'class' => 'btn btn-round btn-primary btn-highly-interested',
                        'data-value' => ($model->highly_interested == 1 ? 0 : 1)
                    ]);
                if($is_schedule==0){ ?>
                    <b class="not-set">* Currently no mail is scheduled for this followup.</b>
                <?php }else{
                    echo "&nbsp;&nbsp;" . Html::a(Yii::t('app', 'View Scheduled Mail'), Yii::$app->urlManager->createAbsoluteUrl(["schedule-followup/index", 'inquiry_id' => $model->id]), [
                            'class' => 'btn btn-round btn-primary',]);
                }
                ?>
            <?php// } ?>
        </div>
    </div>
<?php if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF){?>
    <?php if (count($followup) > 0) { ?>
        <div class="card-header card-custom-height bg-primary text-white followup" data-target="#followup_panel">
            <h5>Followup Details <span class="pull-right glyphicon glyphicon-plus followup-plus"> Show more..</span>
                <span class="pull-right glyphicon glyphicon-minus followup-minus">  Show less..</span></h5>
        </div>

        <div class="card-block card bg-white" id="followup_panel">
            <?php $f_count = count($followup);
            for ($i=0;$i<$f_count;$i++) { ?>
                <div class="form-group row">
                    <?php if ($followup[$i]->by != '') { ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Head:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $followup[$i]->by0->username ?></label>
                        </div>
                    <?php } ?>
                    <?php if ($followup[$i]->date != '') { ?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Date:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= date('M-d-Y', $followup[$i]->date) ?></label>
                        </div>
                    <?php } ?>
                    <?php if($i==$f_count-1){ ?>
                        <div class="col-sm-6">
                            <label class="control-label model-view-label">
                                <strong>Notes:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= "First Follow Up" ?></label>
                        </div>
                    <?php }  else if($followup[$i]->note != '') { ?>
                        <div class="col-sm-6">
                            <label class="control-label model-view-label">
                                <strong>Notes:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $followup[$i]->note ?></label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    <?php } ?>
<?php } ?>
<?php $quotation_count = count($inq_package);
if($quotation_count>1){ ?>
    <?php
    for($i=0;$i<$quotation_count;$i++) {?>
        <div class="card-header card-custom-height bg-primary text-white inquiry<?=$i?>" data-target="#inquiry_panel<?=$i?>">
            <h5>Inquiry Details <span class="pull-right glyphicon glyphicon-plus inquiry-plus"> Show more..</span>
                <span class="pull-right glyphicon glyphicon-minus inquiry-minus">  Show less..</span></h5>
        </div>
        <div class="collapse" id="inquiry_panel<?=$i?>">
        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Passenger Details</strong>
        </div>
        <div class="card-block card bg-white view-part">
            <div class="form-group row">
                <?php if ($inq_package[$i]->passenger_name != '') { ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Name:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->passenger_name ?></label>
                    </div>
                <?php
                }
                if ($inq_package[$i]->passenger_email != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Email:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->passenger_email ?></label>
                    </div>
                <?php
                }
                if ($inq_package[$i]->passenger_mobile != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Mobile:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->passenger_mobile ?></label>
                    </div>
                <?php } ?>
                <div class="form-group">
                <div class="col-sm-12">

                    <?= Html::button('Update',['class'=>'btn btn-round btn-primary pull-right','id'=>'update-btn'])?>
                    </div>
                    </div>
            </div>
        </div>
            <div class="card-block card bg-white update-part">
                <div class="form-group row">
                  <?php $form = ActiveForm::begin([
                      'action' =>['inquiry/quoted-inquiry','id'=>$inq_package[$i]->inquiry->id,'quotation_no'=>'','quotation_id'=>'','quote_id'=> $i]
                  ]); ?>
                    <?php if ($inq_package[$i]->passenger_name != '') { ?>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Passenger Name:</strong>
                            </label>
                            <?= $form->field($inq_package[$i], 'passenger_name')->textInput()->label(false) ?>
                    </div>

                        <?php
                    }
                    if ($inq_package[$i]->passenger_email != '') {
                        ?>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Passenger Email:</strong>
                            </label>
                            <?= $form->field($inq_package[$i], 'passenger_email')->textInput()->label(false) ?>
                        </div>
                        <?php
                    }
                    if ($inq_package[$i]->passenger_mobile != '') {
                        ?>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Passenger Mobile:</strong>
                            </label>
                           <?= $form->field($inq_package[$i], 'passenger_mobile')->textInput()->label(false) ?>
                        </div>
                    <?php } ?>
                    <div class="form-group row">
                <div class="col-sm-12">
                    <?= Html::button('View',['class'=>'btn btn-round btn-primary pull-right','id'=>'view-btn']);    ?>
                   <?= Html::submitButton( 'Update', ['class' => 'btn btn-primary  btn-round pull-right', 'id' => 'add_inquiry']); ?>
                </div>
                </div>
                       <?php ActiveForm::end(); ?>
                </div>
                </div>


        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Travel Details</strong>
        </div>
        <div class="card-block card bg-white">
            <div class="form-group row">
                <?php if ($inq_package[$i]->from_date != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>From Date:</strong>
                        </label>
                        <label
                            class="control-label model-view"><?= Yii::$app->formatter->asDate($inq_package[$i]->from_date) ?></label>
                    </div>
                <?php
                }
                if ($inq_package[$i]->return_date != '') {
                    ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Return Date:</strong>
                        </label>
                        <label
                            class="control-label model-view"><?= Yii::$app->formatter->asDate($inq_package[$i]->return_date); ?></label>
                    </div>
                <?php
                }
                if ($inq_package[$i]->no_of_nights != '') {
                    ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong><?= $inq_package[$i]->no_of_nights . ' Nights' ?></strong>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group row">
                <?php if ($inq_package[$i]->destination != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Destination:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->destination ?></label>
                    </div>
                <?php
                }
                if ($inq_package[$i]->leaving_from != '') {
                    ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Leaving From:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->leaving_from ?></label>
                    </div>
                <?php } ?>
                <?php
                $roomtypes = '';
                if (isset($inq_package[$i]->inquiryPackageRoomTypes)) {
                    foreach ($inq_package[$i]->inquiryPackageRoomTypes as $room) {
                        $roomtypes .= $room->roomType->type;
                        $roomtypes .= ',';
                    }
                    $roomtypes = rtrim($roomtypes, ',');
                }
                ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Room Type:</strong>
                    </label>
                    <label class="control-label model-view"><?= $roomtypes; ?></label>
                </div>
                <?php if ($inq_package[$i]->room_count != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Room Count:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->room_count; ?></label>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group row">
                <?php if ($inq_package[$i]->adult_count != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Adult Count:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->adult_count ?></label>
                    </div>
                <?php } ?>
                <?php if ($inq_package[$i]->children_count != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Children Count:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->children_count ?></label>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group row">
                <?php if (count($inq_package[$i]->inquiryPackageChildAges) > 0) {
                    for ($j = 0; $j < $inq_package[$i]->children_count; $j++) {
                        $child_age = $inq_package[$i]->inquiryPackageChildAges[$j];?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Child <?= $j + 1 ?> Age:</strong>
                            </label>
                            <label class="control-label model-view"><?= $child_age->age . ' yrs' ?></label>
                        </div>
                    <?php
                    }
                } ?>
            </div>
        </div>

        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Inquiry Details</strong>
            <strong class="text-white pull-right"><?= InquiryTypes::$headers[$model->type] ?></strong>
        </div>
        <div class="card-block card bg-white">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Customer Type:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= $model->customer_type != '' ? CustomerTypes::$headers[$model->customer_type] : '' ?></label>
                </div>
                <?php if ($model->agent_id != '') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Agent:</strong>
                        </label>
                        <label class="control-label model-view"><?= $model->agent->name ?></label>
                    </div>
                <?php } ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Source:</strong>
                    </label>
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
                    </label>
                    <label
                        class="control-label model-view"><?= $model->inquiry_head != '' ? $model->inquiryHead->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Quotation Manager:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= $model->quotation_manager != '' ? $model->quotationManager->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Quotation Staff:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= $model->quotation_staff != '' ? $model->quotationStaff->username : 'Not Set' ?></label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Follow Up Manager:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= $model->follow_up_head != '' ? $model->followUpHead->username : 'Not Set' ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Follow Up Staff:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= $model->follow_up_staff != '' ? $model->followUpStaff->username : 'Not Set' ?></label>
                </div>
            </div>
            <?php if ($inq_package[$i]->inquiry_details != '') { ?>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view-label">
                            <strong>Inquiry Details:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package[$i]->inquiry_details ?></label>
                    </div>
                </div>
            <?php } ?>
        </div>
        </div>

        <?php if ($model->type == InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
            <div class="card-header card-custom-height bg-primary text-white hotel<?=$i?>" data-target="#hotel_panel<?=$i?>">
                <h5>Hotel Details <span class="pull-right glyphicon glyphicon-plus hotel-plus"> Show more..</span>
                    <span class="pull-right glyphicon glyphicon-minus hotel-minus">  Show less..</span></h5>
            </div>
            <div class="card bg-white" id="hotel_panel<?=$i?>">
                <div class="card-block">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label model-view"><?=$inq_package[$i]->hotel_details ?></label>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($inq_package[$i]->is_itinerary == InquiryPackage::WITHOUT_ITINERARY && $inq_package[$i]->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT || $inq_package[$i]->is_itinerary == InquiryPackage::WITH_ITINERARY && $inq_package[$i]->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
            <?php if($inq_package[$i]->quotation_details!="") {?>
                <div class="card-header card-custom-height bg-primary text-white quotation<?=$i?>" data-target="#quotation_panel<?=$i?>">
                    <h5>Quotation Details <span class="pull-right glyphicon glyphicon-plus quotation-plus"> Show more..</span>
                        <span class="pull-right glyphicon glyphicon-minus quotation-minus">  Show less..</span></h5>
                </div>
                <div class="card bg-white" id="quotation_panel<?=$i?>">
                    <div class="card-block">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label model-view"><?=$inq_package[$i]->quotation_details ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }else{?>
                <div class="card-header card-custom-height bg-primary text-white package<?=$i?>" data-target="#package_panel<?=$i?>">
                    <h5>Package Details <span class="pull-right glyphicon glyphicon-plus package-plus"> Show more..</span>
                        <span class="pull-right glyphicon glyphicon-minus package-minus">  Show less..</span></h5>
                </div>
                <div class="card bg-white" id="package_panel<?=$i?>">
                    <div class="card-block">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h3 class="text-center">
                                    <?= isset($inq_package[$i]->package_name) ? $inq_package[$i]->package_name : '' ?>
                                </h3>
                                <h5 class="text-center">
                                    <?php if($inq_package[$i]->no_of_nights!=''){
                                        $nights = $inq_package[$i]->no_of_nights;
                                    }else{
                                        if(isset($inq_package[$i]->quotationItineraries))
                                            $nights = $inq_package[$i]->quotationItineraries[0]->no_of_itineraries . ' Nights' ;
                                        else
                                            $nights = 0;
                                    } ?>
                                    <?= isset($nights) ? $nights : '' ?> Nights
                                    / <?= isset($nights) ? $nights + 1 : '' ?> Days
                                </h5>
                                <h6 class="text-center"><?= isset($inq_package[$i]->validity)? $inq_package[$i]->validity :""?></h6>
                            </div>
                        </div>
                    </div>
                    <?php if ($inq_package[$i]->is_itinerary == InquiryPackage::WITH_ITINERARY && count($inq_package[$i]->quotationItineraries) > 0) { ?>

                        <div class="card-header view-header bg-danger-dark">
                            <strong class="text-white">Itinerary Details</strong>
                            <strong
                                class="text-white pull-right"><?= isset($inq_package[$i]->quotationItineraries) ? $inq_package[$i]->quotationItineraries[0]->no_of_itineraries . ' Nights' : '' ?></strong>
                        </div>

                        <div class="card-block">
                            <?php
                            if($inq_package[$i]->no_of_days_nights!=''){
                                $count = $inq_package[$i]->no_of_days_nights;
                            }else{
                                if(isset($inq_package[$i]->quotationItineraries))
                                    $count = count($inq_package[$i]->quotationItineraries);
                                else
                                    $count = 0;
                            }
                            for ($j = 0; $j < $count; $j++) {
                                $it = $inq_package[$i]->quotationItineraries[$j];?>
                                <div class="form-group row itinerary-child">
                                    <div class="col-sm-2 img-div">
                                        <img
                                            src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($inq_package[$i]->package->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                                            class="package-image image responsive" width="100" height="100"
                                            alt="Package banner"/>
                                    </div>

                                    <div class="col-sm-8">
                                        <label class="control-label model-view-label">
                                            <strong><?= $it->title ?></strong>
                                        </label>
                                        <label class="control-label model-view"><?= $it->description ?></label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <?php if($inq_package[$i]->quotationPricings[0]->price != "") { ?>
                        <div class="card-header view-header bg-danger-dark">
                            <strong class="text-white">Pricing Details</strong>
                        </div>
                        <div class="card-block">
                            <div class="form-group row child-form">
                                <?php $count = count($inq_package[$i]->quotationPricings);
                                for ($j = 0; $j < $count; $j++) {
                                    $pm = $inq_package[$i]->quotationPricings[$j];?>

                                    <div class="col-sm-3">
                                        <label class="control-label model-view-label">
                                            <strong><?= $pm->type0->type ?></strong><br/>
                                            <strong><?= $pm->currency->name ?><?= ' ' . $pm->price ?></strong>
                                        </label>
                                    </div>

                                <?php } ?>
                            </div>
                            <div class="form-group row">
                                <?php if ($inq_package[$i]->pricing_details != '') {?>
                                    <div class="col-sm-12 ">

                                        <label
                                            class="control-label model-view"><?= isset($inq_package[$i]->pricing_details) ? $inq_package[$i]->pricing_details : '' ?></label>
                                    </div>

                                <?php } ?>
                            </div>
                            <div class="form-group row">
                                <?php if ($inq_package[$i]->terms_and_conditions != '') { ?>
                                    <div class="col-sm-12">
                                        <label class="control-label model-view-label">
                                            <strong>Terms And Conditions:</strong>
                                        </label><br/>
                                        <label
                                            class="control-label model-view"><?= isset($inq_package[$i]->terms_and_conditions) ? $inq_package[$i]->terms_and_conditions : '' ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>



                    <div class="card-header view-header bg-danger-dark">
                        <strong class="text-white">Other Details</strong>
                    </div>
                    <div class="card-block">
                        <div class="form-group row">
                            <?php if ($inq_package[$i]->package_include != '') { ?>
                                <div class="col-sm-6 ">
                                    <label class="control-label model-view-label">
                                        <strong>Package Includes:</strong>
                                    </label><br/>
                                    <label
                                        class="control-label model-view"><?= isset($inq_package[$i]->package_include) ? $inq_package[$i]->package_include : '' ?></label>
                                </div>
                            <?php } ?>
                            <?php if ($inq_package[$i]->package_exclude != '') { ?>
                                <div class="col-sm-6">
                                    <label class="control-label model-view-label">
                                        <strong>Package Excludes:</strong>
                                    </label><br/>
                                    <label
                                        class="control-label model-view"><?= isset($inq_package[$i]->package_exclude) ? $inq_package[$i]->package_exclude : '' ?></label>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group row">

                            <?php if ($inq_package[$i]->other_info != '') { ?>
                                <div class="col-sm-6">
                                    <label class="control-label model-view-label">
                                        <strong>Other Information:</strong>
                                    </label><br/>
                                    <label
                                        class="control-label model-view"><?= isset($inq_package[$i]->other_info) ? $inq_package[$i]->other_info : '' ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
<?php } else{?>
    <div class="card-header card-custom-height bg-primary text-white inquiry" data-target="#inquiry_panel">
        <h5>Inquiry Details <span class="pull-right glyphicon glyphicon-plus inquiry-plus"> Show more..</span>
            <span class="pull-right glyphicon glyphicon-minus inquiry-minus">  Show less..</span></h5>
    </div>
    <div class="collapse" id="inquiry_panel">
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Passenger Details</strong>
    </div>
        <div class="card-block card bg-white view-part">
            <div class="form-group row">
                <?php if ($inq_package->passenger_name != '') { ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Name:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package->passenger_name ?></label>
                    </div>
                    <?php
                }
                if ($inq_package->passenger_email != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Email:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package->passenger_email ?></label>
                    </div>
                    <?php
                }
                if ($inq_package->passenger_mobile != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Mobile:</strong>
                        </label>
                        <label class="control-label model-view"><?= $inq_package->passenger_mobile ?></label>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-sm-12">

                        <?= Html::button('Update',['class'=>'btn btn-round btn-primary pull-right','id'=>'update-btn'])?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-block card bg-white update-part">
            <div class="form-group row">
                <?php $form = ActiveForm::begin([
                    'action' =>['inquiry/quoted-inquiry','id'=>$inq_package->inquiry->id,'quotation_no'=>'','quotation_id'=>$inq_package->id,'quote_id'=>'']
                ]); ?>
                <?php if ($inq_package->passenger_name != '') { ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Name:</strong>
                        </label>
                        <?= $form->field($inq_package, 'passenger_name')->textInput()->label(false) ?>
                    </div>

                    <?php
                }
                if ($inq_package->passenger_email != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Email:</strong>
                        </label>
                        <?= $form->field($inq_package, 'passenger_email')->textInput()->label(false) ?>
                    </div>
                    <?php
                }
                if ($inq_package->passenger_mobile != '') {
                    ?>
                    <div class="col-sm-4">
                        <label class="control-label model-view-label">
                            <strong>Passenger Mobile:</strong>
                        </label>
                        <?= $form->field($inq_package, 'passenger_mobile')->textInput()->label(false) ?>
                    </div>
                <?php } ?>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <?= Html::button('View',['class'=>'btn btn-round btn-primary pull-right','id'=>'view-btn']);    ?>
                        <?= Html::submitButton( 'Update', ['class' => 'btn btn-primary  btn-round pull-right', 'id' => 'add_inquiry']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Travel Details</strong>
    </div>
    <div class="card-block card bg-white">
        <div class="form-group row">
            <?php if ($inq_package->from_date != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>From Date:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($inq_package->from_date) ?></label>
                </div>
            <?php
            }
            if ($inq_package->return_date != '') {
                ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Return Date:</strong>
                    </label>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($inq_package->return_date); ?></label>
                </div>
            <?php
            }
            if ($inq_package->no_of_nights != '') {
                ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong><?= $inq_package->no_of_nights . ' Nights' ?></strong>
                    </label>
                </div>
            <?php } ?>
        </div>
        <div class="form-group row">
            <?php if ($inq_package->destination != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Destination:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->destination ?></label>
                </div>
            <?php
            }
            if ($inq_package->leaving_from != '') {
                ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Leaving From:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->leaving_from ?></label>
                </div>
            <?php } ?>
            <?php
            $roomtypes = '';
            if (isset($inq_package->inquiryPackageRoomTypes)) {
                foreach ($inq_package->inquiryPackageRoomTypes as $room) {
                    $roomtypes .= $room->roomType->type;
                    $roomtypes .= ',';
                }
                $roomtypes = rtrim($roomtypes, ',');
            }
            ?>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Room Type:</strong>
                </label>
                <label class="control-label model-view"><?= $roomtypes; ?></label>
            </div>
            <?php if ($inq_package->room_count != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Room Count:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->room_count; ?></label>
                </div>
            <?php } ?>
        </div>
        <div class="form-group row">
            <?php if ($inq_package->adult_count != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Adult Count:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->adult_count ?></label>
                </div>
            <?php } ?>
            <?php if ($inq_package->children_count != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Children Count:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->children_count ?></label>
                </div>
            <?php } ?>
        </div>
        <div class="form-group row">
            <?php if (count($inq_package->inquiryPackageChildAges) > 0) {
                for ($i = 0; $i < $inq_package->children_count; $i++) {
                    $child_age = $inq_package->inquiryPackageChildAges[$i];?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Child <?= $i + 1 ?> Age:</strong>
                        </label>
                        <label class="control-label model-view"><?= $child_age->age . ' yrs' ?></label>
                    </div>
                <?php
                }
            } ?>
        </div>
    </div>

    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Inquiry Details</strong>
        <strong class="text-white pull-right"><?= InquiryTypes::$headers[$model->type] ?></strong>
    </div>
    <div class="card-block card bg-white">
        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Customer Type:</strong>
                </label>
                <label
                    class="control-label model-view"><?= $model->customer_type != '' ? CustomerTypes::$headers[$model->customer_type] : '' ?></label>
            </div>
            <?php if ($model->agent_id != '') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Agent:</strong>
                    </label>
                    <label class="control-label model-view"><?= $model->agent->name ?></label>
                </div>
            <?php } ?>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Source:</strong>
                </label>
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
                </label>
                <label
                    class="control-label model-view"><?= $model->inquiry_head != '' ? $model->inquiryHead->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Quotation Manager:</strong>
                </label>
                <label
                    class="control-label model-view"><?= $model->quotation_manager != '' ? $model->quotationManager->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Quotation Staff:</strong>
                </label>
                <label
                    class="control-label model-view"><?= $model->quotation_staff != '' ? $model->quotationStaff->username : 'Not Set' ?></label>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Follow Up Manager:</strong>
                </label>
                <label
                    class="control-label model-view"><?= $model->follow_up_head != '' ? $model->followUpHead->username : 'Not Set' ?></label>
            </div>
            <div class="col-sm-3">
                <label class="control-label model-view-label">
                    <strong>Follow Up Staff:</strong>
                </label>
                <label
                    class="control-label model-view"><?= $model->follow_up_staff != '' ? $model->followUpStaff->username : 'Not Set' ?></label>
            </div>
        </div>
        <?php if ($inq_package->inquiry_details != '') { ?>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label model-view-label">
                        <strong>Inquiry Details:</strong>
                    </label>
                    <label class="control-label model-view"><?= $inq_package->inquiry_details ?></label>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>

    <?php if ($model->type == InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
        <div class="card-header card-custom-height bg-primary text-white hotel" data-target="#hotel_panel">
            <h5>Hotel Details <span class="pull-right glyphicon glyphicon-plus hotel-plus"> Show more..</span>
                <span class="pull-right glyphicon glyphicon-minus hotel-minus">  Show less..</span></h5>
        </div>
        <div class="card bg-white" id="hotel_panel">
            <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view"><?=$inq_package->hotel_details ?></label>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($inq_package->is_itinerary == InquiryPackage::WITHOUT_ITINERARY && $inq_package->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT || $inq_package->is_itinerary == InquiryPackage::WITH_ITINERARY && $inq_package->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
        <?php if($inq_package->quotation_details!=null) {?>
            <div class="card-header card-custom-height bg-primary text-white hotel" data-target="#quotation_panel">
                <h5>Quotation Details <span class="pull-right glyphicon glyphicon-plus quotation-plus"> Show more..</span>
                    <span class="pull-right glyphicon glyphicon-minus quotation-minus">  Show less..</span></h5>
            </div>
            <div class="card bg-white" id="quotation_panel">
                <div class="card-block">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label model-view"><?=$inq_package->quotation_details?></label>
                        </div>
                    </div>
                </div>
            </div>
        <?php }else{?>
            <div class="card-header card-custom-height bg-primary text-white package" data-target="#package_panel">
                <h5>Package Details <span class="pull-right glyphicon glyphicon-plus package-plus"> Show more..</span>
                    <span class="pull-right glyphicon glyphicon-minus package-minus">  Show less..</span></h5>
            </div>
            <div class="card bg-white" id="package_panel">
                <div class="card-block">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <h3 class="text-center">
                                <?= isset($inq_package->package_name) ? $inq_package->package_name : '' ?>
                            </h3>
                            <h5 class="text-center">
                                <?php if($inq_package->no_of_nights!=''){
                                    $nights = $inq_package->no_of_nights;
                                }else{
                                    if(isset($inq_package->quotationItineraries))
                                        $nights = $inq_package->quotationItineraries[0]->no_of_itineraries . ' Nights' ;
                                    else
                                        $nights = 0;
                                } ?>
                                <?= isset($nights) ? $nights : '' ?> Nights
                                / <?= isset($nights) ? $nights + 1 : '' ?> Days
                            </h5>
                        </div>
                    </div>
                </div>
                <?php if ($inq_package->is_itinerary == InquiryPackage::WITH_ITINERARY && count($inq_package->quotationItineraries) > 0) { ?>

                    <div class="card-header view-header bg-danger-dark">
                        <strong class="text-white">Itinerary Details</strong>
                        <strong
                            class="text-white pull-right"><?= isset($inq_package->quotationItineraries) ? $inq_package->quotationItineraries[0]->no_of_itineraries . ' Nights' : '' ?></strong>
                    </div>

                    <div class="card-block">
                        <?php
                        if($inq_package->no_of_days_nights!=''){
                            $count = $inq_package->no_of_days_nights;
                        }else{
                            if(isset($inq_package->quotationItineraries))
                                $count = count($inq_package->quotationItineraries);
                            else
                                $count = 0;
                        }
                        for ($i = 0; $i < $count; $i++) {
                            $it = $inq_package->quotationItineraries[$i];?>
                            <div class="form-group row itinerary-child">
                                <div class="col-sm-2 img-div">
                                    <img
                                        src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($inq_package->package->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                                        class="package-image image responsive" width="100" height="100"
                                        alt="Package banner"/>
                                </div>

                                <div class="col-sm-8">
                                    <label class="control-label model-view-label">
                                        <strong><?= $it->title ?></strong>
                                    </label>
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
                    <div class="form-group row child-form">
                        <?php $count = count($inq_package->quotationPricings);
                        for ($i = 0; $i < $count; $i++) {
                            $pm = $inq_package->quotationPricings[$i];?>

                            <div class="col-sm-3">
                                <label class="control-label model-view-label">
                                    <strong><?= $pm->type0->type ?></strong><br/>
                                    <strong><?= $pm->currency->name ?><?= ' ' . $pm->price ?></strong>
                                </label>
                            </div>

                        <?php } ?>
                    </div>
                    <div class="form-group row">
                        <?php if ($inq_package->terms_and_conditions != '') { ?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Terms And Conditions:</strong>
                                </label><br/>
                                <label
                                    class="control-label model-view"><?= isset($inq_package->terms_and_conditions) ? $inq_package->terms_and_conditions : '' ?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-header view-header bg-danger-dark">
                    <strong class="text-white">Other Details</strong>
                </div>
                <div class="card-block">
                    <div class="form-group row">
                        <?php if ($inq_package->package_include != '') { ?>
                            <div class="col-sm-6 ">
                                <label class="control-label model-view-label">
                                    <strong>Package Includes:</strong>
                                </label><br/>
                                <label
                                    class="control-label model-view"><?= isset($inq_package->package_include) ? $inq_package->package_include : '' ?></label>
                            </div>
                        <?php } ?>
                        <?php if ($inq_package->package_exclude != '') { ?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Package Excludes:</strong>
                                </label><br/>
                                <label
                                    class="control-label model-view"><?= isset($inq_package->package_exclude) ? $inq_package->package_exclude : '' ?></label>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group row">

                        <?php if ($inq_package->other_info != '') { ?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Other Information:</strong>
                                </label><br/>
                                <label
                                    class="control-label model-view"><?= isset($inq_package->other_info) ? $inq_package->other_info : '' ?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>
    <div class="modal bs-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Change Status Of Inquiry</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label">Status</label>
                                <?= $form->field($new_model, 'status')->dropDownList(InquiryStatusTypes::$titles, ['id' => 'status', 'prompt' => 'Select Status'])->label(false); ?>
                            </div>
                            <div class="col-sm-8 followupdate_div" style="display: none;">
                                <?= $form->field($followup_model, 'date')->textInput(['class' => 'followupdate form-control', 'name' => 'Followup[date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d', 'value' => date("M-d-Y", time()+86400)]); ?>
                            </div>
                        </div>
                        <div class="form-group row followupdate_div" style="display: none;">
                            <?php $total_quotation = count($model->inquiryPackages);
                            for($q=0;$q<$total_quotation;$q++){ $no= $q+1;?>
                                <div class="col-sm-3">
                                    <input type=radio value="<?= $model->inquiryPackages[$q]->id ?>" name="Followup[inquiry_package_id]" checked="<?=$q==0 ? "checked" : ""?>"> <a href="<?=Yii::$app->getUrlManager()->createUrl(['/inquiry/quoted-inquiry', 'id' => $model->id, 'quotation_no' => '', 'quotation_id' => $model->inquiryPackages[$q]->id])?>" target="_blank"> <?= "Quotation $no" ?></a>
                                </div>
                            <?php }?>

                        </div>

                        <div class="price form-group row" style="display: none;">
                            <div class="col-sm-3">
                                <?= $form->field($booking_model, 'currency_id')->dropDownList(Currency::getCurrency()); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($booking_model, 'inr_rate')->textInput(['value' => 1]); ?>
                                <div id="rate_error" class="help-block error-hint" style="display: none">Rate cannot be blank.</div>

                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($booking_model, 'final_price')->textInput(); ?>
                                <div id="price_error" class="help-block error-hint" style="display: none">Final Price cannot be blank.</div>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($booking_model, 'booking_staff')->dropDownList(User::getHead()); ?>
                            </div>

                        </div>

                        <div class="form-group row price" style="display: none;">
                            <?php $total_quotation = count($model->inquiryPackages);
                                for($q=0;$q<$total_quotation;$q++){ $no= $q+1;?>
                                <div class="col-sm-3">
                                    <input type=radio value="<?= $model->inquiryPackages[$q]->id ?>" name="Booking[inquiry_package_id]" checked="<?=$q==0 ? "checked" : ""?>"> <a href="<?=Yii::$app->getUrlManager()->createUrl(['/inquiry/quoted-inquiry', 'id' => $model->id, 'quotation_no' => '', 'quotation_id' => $model->inquiryPackages[$q]->id])?>" target="_blank"> <?= "Quotation $no" ?></a>
                                </div>
                            <?php }?>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label">Notes</label>
                                <?= $form->field($new_model, 'notes')->textarea(['rows' => 6, 'class' => 'summernote','id'=>'update-inquiry-notes'])->label(false) ?>
                                <div id="notes_error2" class="help-block error-hint" style="display: none">Notes cannot be blank.</div>
                            </div>
                        </div>
                        <?= Html::hiddenInput('inquiry_id', $model->id, ['id' => 'inquiry_id']); ?>

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
    <div class="modal schedule-followup-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Schedule Followup Mail</h4>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group form-material">

                        <div class="form-group row">
                            <div id="schedule_date" class="col-sm-6 field-schedulefollowup-scheduled_at">
                                <label class="control-label">Date</label>
                                <?=Html::textInput('schedule_date',date("M-d-Y", time()+86400),['class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d'])?>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Time</label>
                                <?=Html::textInput('schedule_time',"11:00",['class' => 'form-control', 'id' => "tm"])?>
                            </div>
                        </div>
                        <div class="form-group row price" >
                            <?php $total_quotation = count($model->inquiryPackages);
                            for($q=0;$q<$total_quotation;$q++){ $no= $q+1;?>
                                <div class="col-sm-3">
                                    <input type=radio value="<?= $model->inquiryPackages[$q]->id ?>" name="schedule_inquiry_package_id" checked="<?=$q==0 ? "checked" : ""?>"> <a href="<?=Yii::$app->getUrlManager()->createUrl(['/inquiry/quoted-inquiry', 'id' => $model->id, 'quotation_no' => '', 'quotation_id' => $model->inquiryPackages[$q]->id])?>" target="_blank"> <?= "Quotation $no" ?></a>
                                </div>
                            <?php }?>

                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label class="control-label">Body</label>
                                <?= $form->field($schedule_followup, 'text_body')->textarea(['rows' => 6, 'class' => 'summernote'])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer no-border">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?= Html::submitButton('Schedule', ['class' => 'btn btn-success pull-right']) ?>
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
                                    <?= $form->field($model, 'notes')->textarea(['class'=>'summernote','value' => '']) ?>
                                    <div id="notes_error" class="help-block error-hint" style="display: none">Notes cannot be blank.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer no-border">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?= Html::submitButton('Cancel', ['class' => 'btn btn-danger pull-right', 'id' => 'notes']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->registerJs('
 $("document").ready(function(){
 $(".update-part").hide();
  $(".dropdown-toggle").remove();
  $(".btn-codeview").remove();
$(".followup-plus").hide();
$(".inquiry-minus").hide();
 $(".package-plus").hide();
 $(".hotel-plus").hide();
 $(".quotation-plus").hide();

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
$(".quotation").on("click", function (e) {
    var actives = $(this).attr("data-target");
    $(actives).slideToggle("collapse");
    $(".quotation-minus,.quotation-plus").toggle("hide");
});
var count = '.count($inq_package).';
for(var i=0;i<count;i++){

     $(".inquiry-minus"+i+"").hide();
     $(".package-plus"+i+"").hide();
     $(".hotel-plus"+i+"").hide();
     $(".quotation-plus"+i+"").hide();

     $(".inquiry"+i+"").on("click", function (e) {

      var actives = $(this).attr("data-target");
        $(actives).slideToggle("collapse");
        $(this).find(".inquiry-minus,.inquiry-plus").toggle("hide");


    });
     $(".package"+i+"").on("click", function (e) {
        var actives = $(this).attr("data-target");
        $(actives).slideToggle("collapse");
        $(this).find(".package-minus,.package-plus").toggle("hide");
    });
     $(".hotel"+i+"").on("click", function (e) {
        var actives = $(this).attr("data-target");
        $(actives).slideToggle("collapse");
        $(this).find(".hotel-minus,.hotel-plus").toggle("hide");
    });
    $(".quotation"+i+"").on("click", function (e) {
        var actives = $(this).attr("data-target");
        $(actives).slideToggle("collapse");
        $(this).find(".quotation-minus,.quotation-plus").toggle("hide");
    });
}

$("#update-btn").click(function(){

$(".view-part").hide();
$(".update-part").show();


});
$("#view-btn").click(function(){

$(".view-part").show();
$(".update-part").hide();

});


$("#notes").on("click", function (e) {


     var notes = $("#inquiry-notes").val();
     //alert(notes);
         if(notes == ""){
                $("#notes_error").show();
                 return false;
           }
           else{
                $("#notes_error").hide();
                  return true;
           }
});
$("#status-btn").on("click", function (e) {
            var notes =$("#update-inquiry-notes").val();
            var status =$("#status").val();

          if(status=='.InquiryStatusTypes::COMPLETED.'){
            var final_price = $("#booking-final_price").val();
            var inr_rate = $("#booking-inr_rate").val();
             if(final_price==""){
                $("#price_error").show();
                 $("#rate_error").hide();
                 return false;
             }else if(inr_rate==""){
                $("#price_error").hide();
                $("#rate_error").show();
                  return false;
           }
           else if(notes==""){
                $("#notes_error2").show();
                 return false;
           }
           else{
                $("#notes_error2").hide();
                $("#rate_error").hide();
                $("#price_error").hide();
                  return true;
           }
          }else if(notes==""){
                $("#notes_error2").show();
                $("#rate_error").hide();
                $("#price_error").hide();
                 return false;
           }
           else{
                $("#notes_error2").hide();
                $("#rate_error").hide();
                $("#price_error").hide();
                  return true;
           }
       });


 $("#status").change(function(){

    var status=$(\'#status\').children(\'option\').filter(\':selected\').val();

    if(status=='.InquiryStatusTypes::QUOTED.')
    {
        $(\'.followupdate_div\').show();
    }else
    {
        $(\'.followupdate_div\').hide();
    }

     if(status=='.InquiryStatusTypes::COMPLETED.')
    {
        $(\'.price\').show();
    }else
    {
        $(\'.price\').hide();
    }
    });
 });
$("#tm").clockpicker({
        autoclose: true,
    });

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
    })


'); ?>