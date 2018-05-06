<?php
use backend\models\enums\CategoryTypes;
use backend\models\enums\DirectoryTypes;
use yii\helpers\Url;
?>

<div class="view-div">

    <?php if (isset($model->name)) { ?>
        <div class="card-header view-header bg-danger-dark">

            <?php if(isset($model->itineraries[0])) { ?>
                <strong class="text-white">Itinerary Details</strong>
                <?php
            } else {?>
            <strong class="text-white">Package Details</strong>
            <?php } ?>
            <strong class="text-white pull-right"><?php // $model->itineraries[0]->no_of_itineraries . ' Nights'?></strong>
            <strong class="text-white pull-right"><?= CategoryTypes::$headers[$model->category] ?></strong>
        </div>
        <div class="card-block">

            <div class="form-group row">
                <div class="col-sm-12">
                    <h3 class="text-center">
                        <?= isset($model->name)? strtoupper($model->name):''?>
                    </h3>
                    <h5 class="text-center"><?php $nights =isset($model->no_of_days_nights)? $model->no_of_days_nights : '' ?>
                        <?= isset($nights)? $nights : ''?> Nights / <?= isset($nights)? $nights+1 : ''?> Days
                    </h5>

                    <h6 class="text-center"><?= ($model->validity != '')? $model->validity.'&nbsp; To &nbsp;' :''?><?= isset($model->till_validity)? $model->till_validity :''?></h6>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label model-view-label">
                        <strong><?= isset($model->itinerary_name)?$model->itinerary_name:'' ?></strong>
                    </label>
                </div>
            </div>

            <?php $count = count($model->itineraries);
                  if($count >0 ){
            for ($i = 0; $i < $count; $i++) {
                $it = $model->itineraries[$i];?>
                <div class="form-group row">
                    <?php if (isset($it->media_id)) { ?>
                    <div class="col-sm-2 img-div">
                        <img
                            src="<?php echo isset($it->media_id) ? DirectoryTypes::getPackageDirectory($model->id, true) . $it->media->file_name : Url::to('@web/images/image.jpg', true); ?> "
                            class="package-image image responsive" width="100" height="100"
                            alt="Package banner"/>
                    </div>
                    <?php } ?>

                    <div class="col-sm-8">
                        <label class="control-label model-view-label">
                            <strong><?= $it->title ?></strong>
                        </label><br/>
                        <label class="control-label model-view"><?= $it->description ?></label>
                    </div>

                </div>
            <?php }} ?>
        </div>
    <?php } ?>

    <?php

    if($model->packagePricings[0]->type0 != ''){?>
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Pricing Details</strong>
    </div>
    <div class="card-block">
        <div class="form-group row">
        <?php $count = count($model->packagePricings);
        for ($i = 0; $i < $count; $i++) {
            $pm = $model->packagePricings[$i];
            //print_r($pm->type0); exit;
            if (isset($pm->type0)) {
                ?>

                <div class="col-sm-3">
                    <label class="control-label model-view-label">
                        <?= isset($pm->type0) ? $pm->type0->type : '' ?><br/>
                        <strong><?= $pm->currency->name . ' ' . $pm->price ?></strong>
                    </label>
                </div>

            <?php }else
            { ?>
                <div class="col-sm-3">
                    <label class="control-label model-view-label">

                        <strong>Not Available</strong>
                    </label>
                </div>
            <?php }
        }?>
        </div>
        <div class="form-group row">
        <?php if($model->pricing_details != ''){?>
                   <div class="col-sm-12">
                 <label class="control-label model-view"><?= $model->pricing_details ?></label>
                </div>
            <?php }?>
        </div>
        <div class="form-group row">
            <?php if($model->terms_and_conditions!=''){?>
                <div class="col-sm-12">
                    <label class="control-label model-view-label">
                        <strong>Terms And Conditions:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->terms_and_conditions ?></label>
                </div>
            <?php } ?>
            </div>
    </div>
           <?php } ?>
    <?php if($model->package_include!='' || $model->package_exclude!='' || $model->other_info!=''){?>
    <div class="card-header view-header bg-danger-dark">
        <strong class="text-white">Other Details</strong>

    </div>
    <div class="card-block">

        <div class="form-group row">
            <?php if($model->package_include!=''){?>
                <div class="col-sm-6">
                    <label class="control-label model-view-label">
                        <strong>Package Includes:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->package_include ?></label>
                </div>
            <?php } ?>
            <?php if($model->package_exclude!=''){?>
                <div class="col-sm-6">
                    <label class="control-label model-view-label">
                        <strong>Package Excludes:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->package_exclude ?></label>
                </div>
            <?php } ?>
        </div>

        <div class="form-group row">

            <?php if($model->other_info!=''){?>
                <div class="col-sm-6">
                    <label class="control-label model-view-label">
                        <strong>Additional Details:</strong>
                    </label><br/>
                    <label class="control-label model-view"><?= $model->other_info ?></label>
                </div>
            <?php } ?>
        </div>

    </div>
    <?php }?>
</div>