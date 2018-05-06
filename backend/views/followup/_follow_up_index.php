<?php


use backend\models\enums\CategoryTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\PackageTypes;
use backend\models\enums\PricingTypes;
use common\models\Followup;
use yii\helpers\Html;

?>


<div class="card bg-white">



    <div class="card-header card-custom-height bg-primary text-white" data-target="#demo<?= $model->id ?>">
        <h4><?= "KR - " . $model->inquiryPackage->inquiry->inquiry_id ?> &nbsp;&nbsp;   <?= $model->inquiryPackage->passenger_email ?>
            &nbsp;&nbsp;   <?= $model->inquiryPackage->passenger_mobile ?><span class="pull-right">  <?= Yii::$app->formatter->asDate($model->date) ?></span> </h4>
       <h4> <?= $model->inquiryPackage->destination   ?>
            <span class="pull-right">   <?php echo Html::a(Yii::t('app', 'Change Status'), '#', [
                    'class' => 'btn btn-round btn-default edit',
                    'data-toggle' => 'modal',
                    'data-target' => '.bs-modal-sm',
                    'data-id'=> $model->inquiry_id,
                ]); ?></span></h4>

    </div>

    <div id="demo<?= $model->id ?>" class="card-block collapse">

        <div class="card-header view-header bg-danger-dark">
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/quoted-inquiry", "id" => $model->inquiry_id]) ?>">
                <strong class="text-white"><u>Inquiry Details</u></strong>
            </a>
            <strong class="text-white pull-right"><?= InquiryTypes:: $headers[$model->inquiryPackage->inquiry->type] ?></strong>
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
                            <strong>From Date:</strong>
                        </label><br/>
                        <label class="control-label model-view"><?= Yii::$app->formatter->asDate($model->inquiryPackage->from_date) ?></label>
                    </div>

                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Return Date:</strong>
                        </label><br/>
                        <label class="control-label model-view"><?= Yii::$app->formatter->asDate($model->inquiryPackage->return_date); ?></label>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong><?=$model->inquiryPackage->no_of_nights!='' ? $model->inquiryPackage->no_of_nights . ' Nights' : ''; ?></strong>
                        </label><br/>
                    </div>
                </div>
            <?php
            $roomtypes='';
            if (isset($model->inquiryPackage->inquiryPackageRoomTypes)) {
                foreach ($model->inquiryPackage->inquiryPackageRoomTypes as $room) {
                    $roomtypes .= $room->roomType->type;
                    $roomtypes .= ',';
                }
                $roomtypes = rtrim($roomtypes, ',');
            }
            ?>
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
                        <strong>Room Type:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $roomtypes; ?></label>
                </div>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Room Count:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->inquiryPackage->room_count; ?></label>
                </div>
            </div>

            <div class="form-group row">
                <?php if($model->inquiryPackage->adult_count!='') { ?>
                    <div class="col-sm-3">
                        <label class="control-label model-view-label">
                            <strong>Adult Count:</strong>
                        </label>
                        <label class="control-label model-view"><?= $model->inquiryPackage->adult_count ?></label>
                    </div>
                <?php } ?>
                <?php if($model->inquiryPackage->children_count!='') { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <strong>Children Count:</strong>
                    </label>
                    <label class="control-label model-view"><?= $model->inquiryPackage->children_count ?></label>
                </div>
                <?php }?>
            </div>
            <div class="form-group row">
                <?php if(count($model->inquiryPackage->inquiryPackageChildAges)>0){
                    for($i=0;$i<$model->inquiryPackage->children_count;$i++){
                        $child_age = $model->inquiryPackage->inquiryPackageChildAges[$i];?>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Child <?=$i+1?> Age:</strong>
                            </label>
                            <label class="control-label model-view"><?= $child_age->age . ' yrs'?></label>
                        </div>
                    <?php } }?>
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

            <?php if ($model->inquiryPackage->inquiry->type != InquiryTypes::PER_ROOM_PER_NIGHT) { ?>
                <div class="card-header view-header bg-danger-dark">
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/quoted-inquiry", "id" => $model->inquiry_id]) ?>">
                        <strong class="text-white"><u>Package Details</u></strong>
                    </a>
                    <strong class="text-white pull-right"><?=$model->inquiryPackage->package->category!='' ? CategoryTypes::$headers[$model->inquiryPackage->package->category] : ''?></strong>
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

                    <!--    <div class="table-responsive">
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
            <?php }  else { ?>
                <div class="card-header view-header bg-danger-dark">
                    <strong class="text-white">Hotel Details</strong>
                </div>
                <div class="card-block">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label model-view-label">
                            <strong>Hotel Details:</strong>
                        </label><br/>
                        <label class="control-label model-view"><?= $model->inquiryPackage->hotel_details ?></label>
                    </div>
                </div>
             </div>
            <?php } ?>


        <div class="card-header view-header bg-danger-dark">
            <strong class="text-white">Follow Up Details</strong>
        </div>
        <div class="card-block">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label model-view"><?= $model->note ?></label>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$this->registerJs('




$(".card-header").on("click", function (e) {
var actives = $(this).attr("data-target");
$(actives).slideToggle("collapse");
});


'); ?>