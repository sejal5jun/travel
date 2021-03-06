<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 30-08-2016
 * Time: 17:08
 */
use backend\models\enums\CategoryTypes;

?>

<?php foreach($models as $model):?>
    <div class="col-sm-3">
        <div class="card">


            <?php if ($model->category == CategoryTypes::DOMESTIC_HOLIDAYS) { ?>
                <div id="<?= $model->id ?>" class="card-header card-custom card-custom-height bg-info-darker text-white package-header" data-id="<?= $model->id ?>">
             <?php } ?>
             <?php if ($model->category == CategoryTypes::INTERNATIONAL_HOLIDAYS) { ?>
                <div id="<?= $model->id ?>" class="card-header card-custom card-custom-height bg-success-darker text-white package-header" data-id="<?= $model->id ?>">
            <?php } ?>
            <?php if ($model->category == CategoryTypes::LUXURY_HOLIDAYS) { ?>
                <div  id="<?= $model->id ?>" class="card-header card-custom ard-custom-height bg-warning-darker text-white package-header" data-id="<?= $model->id ?>">
            <?php } ?>
            <?php if ($model->category == CategoryTypes::HONEYMOONS_CORNER) { ?>
                <div id="<?= $model->id ?>" class="card-header card-custom card-custom-height bg-danger-darker text-white package-header" data-id="<?= $model->id ?>">
            <?php } ?>
            <?php if ($model->category == CategoryTypes::WEEKEND_GATEWAYS) { ?>
                 <div id="<?= $model->id ?>" class="card-header card-custom card-custom-height bg-primary-darker text-white package-header" data-id="<?= $model->id ?>">
             <?php } ?>
    <h5 class="ellipsis"  alt="<?=strtoupper($model->name) ?>"> <?= strtoupper($model->name) ?></h5>
    <p class="pull-right" style="font-size:12px;margin:-13px -10px;"><?= $model->validity ?></p>


    <p class="ellipsis"><?= $model->itinerary_name?></p>
    <p><?php
        $nights=''; $days='';
        $nights=$model->no_of_days_nights;
        $days=$nights +1;
        echo $nights." Nights / " .$days. " Days" ?>
    </p>
    <p>
        <?= $model->packagePricings[0]->price?> &nbsp;<?= strtoupper($model->packagePricings[0]->currency->name)?>
    </p>
    <p style="margin-right: -15px">
        <?php if($model->for_agent == 1){?><span class="label label-default pull-right">Agent</span><?php }?>
    </p>
    </div>

        </div>
    </div>
<?php endforeach?>