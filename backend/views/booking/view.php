<?php

use backend\models\enums\CategoryTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\PricingTypes;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */

$this->title = "Booking";
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-view">
    <div class="card bg-white">
        <div class="card-header bg-danger-dark">
            <strong class="text-white">Booking Details</strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label model-view-label"><strong><?= $model->booking_id ?></strong></label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label model-view"><?= $model->inquiry->notes ?></label>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white">

        <div class="card-header bg-danger-dark view-header">
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", "id" => $model->inquiry_id]) ?>">
                <strong class="text-white"><u>Inquiry Details</u></strong>
            </a>
            <strong
                class="text-white pull-right"><?= InquiryTypes:: $headers[$model->inquiryPackage->inquiry->type] ?></strong>
        </div>

        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Passenger Name:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->passenger_name ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Passenger Email:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->passenger_email ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Passenger Mobile:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->passenger_mobile ?></label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Destination:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->destination ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Leaving From:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->leaving_from ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>No Of Rooms:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->room_count ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Room Type:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $roomtypes ?></label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>From Date:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($model->inquiryPackage->from_date) ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>No Of Nights:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->no_of_days_nights ?></label>
                </div>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Return Date:</strong>
                    </label><br/>
                    <label
                        class="control-label model-view"><?= Yii::$app->formatter->asDate($model->inquiryPackage->return_date); ?></label>
                </div>
            </div>
            <div class="form-group row">

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Adult Count:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->adult_count ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Children Count:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->children_count ?></label>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label model-view-label">
                        <strong>Inquiry Details:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->inquiry_details ?></label>
                </div>
            </div>

        </div>
    </div>

    <div class="card bg-white">
            <?php if ($model->inquiryPackage->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
                <div class="card-header bg-danger-dark view-header">
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", "id" => $model->inquiry_id]) ?>">
                        <strong class="text-white"><u>Package Details</u></strong>
                    </a>
                    <strong class="text-white pull-right"><?=CategoryTypes::$headers[$model->inquiryPackage->package->category]?></strong>
                </div>
                <div class="card-block">
                    <div class="form-group row">

                        <div class="col-sm-12">
                            <h3 class="text-center">
                                <?= isset($model->inquiryPackage->package_name)? $model->inquiryPackage->package_name:''?>
                            </h3>
                            <h5 class="text-center"><?php $nights =isset($model->inquiryPackage->no_of_days_nights)? $model->inquiryPackage->no_of_days_nights: '' ?>
                                <?= isset($nights)? $nights : ''?> Nights / <?= isset($nights)? $nights+1 : ''?> Days
                            </h5>
                        </div>

                    </div>
                    <div class="form-group row">
                        <?php if($model->inquiryPackage->package_include!=''){?>
                        <div class="col-sm-6 ">
                            <label class="control-label model-view-label">
                                <strong>Package Includes:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= isset($model->inquiryPackage->package_include)? $model->inquiryPackage->package_include:''?></label>
                        </div>
                       <?php } ?>
                        <?php if($model->inquiryPackage->package_exclude!=''){?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Package Excludes:</strong>
                                </label><br/>
                                <label class="control-label model-view"><?= isset($model->inquiryPackage->package_exclude)? $model->inquiryPackage->package_exclude:''?></label>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="form-group row">
                        <?php if($model->inquiryPackage->terms_and_conditions!=''){?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Terms And Conditions:</strong>
                                </label><br/>
                                <label class="control-label model-view"><?= isset($model->inquiryPackage->terms_and_conditions)? $model->inquiryPackage->terms_and_conditions:'' ?></label>
                            </div>
                        <?php } ?>
                        <?php if($model->inquiryPackage->other_info!=''){?>
                            <div class="col-sm-6">
                                <label class="control-label model-view-label">
                                    <strong>Other Information:</strong>
                                </label><br/>
                                <label class="control-label model-view"><?= isset($model->inquiryPackage->other_info)? $model->inquiryPackage->other_info:'' ?></label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view-label">
                            <strong>Package Price:</strong>
                        </label><br/>

                       <!-- <div class="table-responsive">
                            <table class="table table-bordered table-striped m-b-0">
                                <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Type</th>
                                    <th>Price</th>

                                </tr>
                                </thead>
                                <tbody><?php /*foreach ($model->inquiryPackage->package->packagePricings as $values) { */?>
                                    <tr>
                                        <td><?/*= $values->currency->name */?></td>
                                        <td><?/*= PricingTypes::$headers[$values->type] */?></td>
                                        <td><?/*= $values->price */?></td>

                                    </tr>


                                <?php /*} */?></tbody>
                            </table>
                        </div>-->

                        <?php $count = count($model->inquiryPackage->quotationPricings);
                        for($i=0;$i<$count;$i++){
                            $pm = $model->inquiryPackage->quotationPricings[$i];?>

                            <div class="col-sm-3">
                                <label class="control-label model-view">
                                    <?= PricingTypes::$headers[$pm->type]?><br/>
                                    <strong><?= $pm->currency->name?><?= ' ' .$pm->price?></strong>
                                </label>
                            </div>

                        <?php } ?>
                    </div>


                </div>
            </div>
            <?php } else { ?>
                <div class="card-header bg-danger-dark view-header">
                    <strong class="text-white">Hotel Details</strong>
                </div>
                <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view"><?= $model->inquiryPackage->hotel_details ?></label>
                    </div>
                </div>
            </div>
            <?php } ?>
    </div>

</div>
