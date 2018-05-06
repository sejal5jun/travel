<?php
use backend\models\enums\ActivityTypes;
use backend\models\enums\CategoryTypes;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\UserTypes;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Inquiry Activity: ' . 'KR-' . $inquiry->inquiry_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inquiries'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("inquiry/index")]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'KR-' . $inquiry->inquiry_id), 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id])]];
$this->params['breadcrumbs'][] = $this->title;;
?>
<div class="page-title">
    <div class="title"><?=Html::encode('KR-' . $inquiry->inquiry_id . '/' . $inquiry->name . '/' . $inquiry->mobile . '/' . $inquiry->email)?></div>
    <div class="sub-title">Inquiry activities</div>
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
<div class="timeline stacked">
    <?php
    $f=1;
    $q=0;
    ?>
    <?php foreach($model as $m){?>
    <div class="timeline-card">
        <div class="timeline-icon">
            <?php if ($m->createdBy->role == UserTypes::ADMIN) { ?>
                <img
                    src="<?php echo isset($m->createdBy->media_id) ? DirectoryTypes::getAdminDirectory(true) . $m->createdBy->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                    alt="user" class="header-avatar img-circle" height="50" width="50"/>
            <?php }  else if ($m->createdBy->role == UserTypes::QUOTATION_MANAGER) { ?>
                <img
                    src="<?php echo isset($m->createdBy->media_id) ? DirectoryTypes::getQuotationManagerDirectory(true) . $m->createdBy->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                    alt="user" class="header-avatar img-circle" height="50" width="50"/>
            <?php } else if ($m->createdBy->role == UserTypes::FOLLOW_UP_MANAGER) { ?>
                <img
                    src="<?php echo isset($m->createdBy->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $m->createdBy->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                    alt="user" class="header-avatar img-circle" height="50" width="50"/>
            <?php } else if($m->createdBy->role==UserTypes::QUOTATION_STAFF || $m->createdBy->role==UserTypes::FOLLOW_UP_STAFF || $m->createdBy->role==UserTypes::BOOKING_STAFF){?>
                <img
                    src="<?php echo isset($m->createdBy->media_id) ? DirectoryTypes::getStaffDirectory(true) . $m->createdBy->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                    alt="user" class="header-avatar img-circle" height="50" width="50"/>
            <?php } else {?>
                <img
                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                    alt="user" class="header-avatar img-circle" height="50" width="50"/>
            <?php } ?>
        </div>
        <?php if($m->activity==ActivityTypes::ADDED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong class="view-header-label">
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id, 'activity' => $m->activity]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">
                        <?=$m->inquiry->travelling_details?>
                    </label>
                    <label class="col-sm-3">
                        <strong>From: </strong>
                        <?=date('M-d-Y',$m->inquiry->from_date)?>
                    </label>
                    <label class="col-sm-3">
                        <strong>To: </strong>
                        <?=date('M-d-Y',$m->inquiry->return_date)?>
                    </label>
                </div>
                <div class="row">
                    <label class="col-sm-3"><strong>Inquiry Head: </strong><?=$m->inquiry->inquiryHead->username?></label>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::UPDATED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id, 'activity' => $m->activity]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>

                <div class="row">
                    <label class="col-sm-3">
                        <?=$m->inquiry->travelling_details?>
                    </label>
                    <label class="col-sm-3">
                        <strong>From: </strong>
                        <?=date('M-d-Y',$m->inquiry->from_date)?>
                    </label>
                    <label class="col-sm-3">
                        <strong>To: </strong>
                        <?=date('M-d-Y',$m->inquiry->return_date)?>
                    </label>
                </div>
                <div class="row">
                    <label class="col-sm-3"><strong>Inquiry Head: </strong><?=$m->inquiry->inquiryHead->username?></label>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::QUOTED && isset($m->inquiry->inquiryPackages[$q])){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/quoted-inquiry", 'id' => $inquiry->id, 'quotation' => $q]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>
                <div class="row">
                    <?php if($m->inquiry->inquiryPackages[$q]->package_id!=''){?>
                         <label class="col-sm-3">
                             <strong><?=$m->inquiry->inquiryPackages[$q]->package_name?></strong>
                         </label>
                        <label class="col-sm-3">
                            <?=CategoryTypes::$headers[$m->inquiry->inquiryPackages[$q]->package->category]?>
                        </label>
                        <?php if($m->inquiry->inquiryPackages[$q]->no_of_nights!=''){
                            $nights = $m->inquiry->inquiryPackages[$q]->no_of_nights;
                            $days = $m->inquiry->inquiryPackages[$q]->no_of_nights+1;?>
                            <label class="col-sm-3">
                                <?=$nights . ' Nights/' . $days . ' Days'?>
                            </label>
                        <?php } ?>
                    <?php }?>
                </div>
                <div class="row">
                    <label class="col-sm-3"><strong>Quotation Manager: </strong><?=$m->inquiry->quotationManager->username?></label>
                    <?php if($m->inquiry->quotation_staff!=''){?>
                        <label class="col-sm-3"><strong>Quotation Staff: </strong><?=$m->inquiry->quotationStaff->username?></label>
                    <?php } ?>
                </div>
                <?php $q++;?>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::FOLLOWED_UP){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <?='Inquiry ' . ActivityTypes::$headers[$m->activity]?>
                    </strong>
                    <?= 'is taken by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>
                <div class="row">
                    <label class="col-sm-12">
                        <?php if(isset($m->inquiry->followups[$f]) ){
                            echo '<strong>Date: </strong>' .date('M-d-Y',$m->inquiry->followups[$f]->date);
                        } ?>
                    </label>
                    <label class="col-sm-12">
                        <?php if(isset($m->inquiry->followups[$f])){
                            if($m->inquiry->followups[$f]->note!='')
                                 echo '<strong>Notes: </strong>' . $m->inquiry->followups[$f]->note ;
                            else
                                echo '<strong>Notes: </strong>' .$m->notes;
                        } else{
                            echo '<strong>Notes: </strong>' .$m->notes;
                        }?>
                    </label>
                </div>

                <div class="row">
                    <label class="col-sm-3"><strong>Follow Up Head: </strong><?=$m->inquiry->quotationManager->username?></label>
                    <?php if($m->inquiry->follow_up_staff!=''){?>
                        <label class="col-sm-3"><strong>Follow Up Staff: </strong><?=$m->inquiry->followUpStaff->username?></label>
                    <?php } ?>
                </div>
                <?php $f++;?>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::AMENDED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>
                <div class="row">
                    <label class="col-sm-12">
                        <strong>Notes: </strong><?=$m->notes?>
                    </label>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::COMPLETED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>

                <div class="row">
                    <?php if(isset($m->inquiry->bookings[0])){?>
                    <label class="col-sm-3">
                        <strong>Booking id:</strong> <?=$m->inquiry->bookings[0]->booking_id?>
                    </label>
                    <?php }?>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::VOUCHERED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>

                <div class="row">
                    <?php if(isset($m->inquiry->bookings[0])){?>
                        <label class="col-sm-3">
                            <strong>Booking id:</strong> <?=$m->inquiry->bookings[0]->booking_id?>
                        </label>
                    <?php }?>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::CANCELLED){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inquiry->id]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>

                <div class="row">
                    <label class="col-sm-12">
                        <?=$m->notes?>
                    </label>
                </div>
            </section>
        <?php } ?>
        <?php if($m->activity==ActivityTypes::SCHEDULED_MAIL){?>
            <section class="timeline-content">
                <div class="timeline-heading border">
                    <strong>
                        <a href="<?=Yii::$app->urlManager->createAbsoluteUrl(["schedule-followup/index", 'id' => $inquiry->id]) ?>">
                            <?='Inquiry is ' . ActivityTypes::$headers[$m->activity]?>
                        </a>
                    </strong>
                    <?= 'by'?> <?= $m->createdBy->username?>
                    <div class="pull-right"> <i class="fa fa-history"></i>  <?= Yii::$app->formatter->asDatetime($m->created_at)?></div>
                </div>

                <div class="row">
                    <label class="col-sm-12">
                        <?=$m->notes?>
                    </label>
                </div>
            </section>
        <?php } ?>
    </div>
    <?php } ?>

    <div class="timeline-card">
        <div class="timeline-icon bg-default">
            <i class="icon-paper-plane"></i>
        </div>
    </div>
</div>
<?php
$this->registerJs('
    $(document).ready(function(){
     /*   var colors = ["#ee9f47","#4daf61","#43b6df"];
        var rand = Math.floor(Math.random()*colors.length);
        $(".random").css("background-color", "#43b6df");*/
    });
');