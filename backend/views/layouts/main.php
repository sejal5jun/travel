<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\models\enums\DirectoryTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use common\models\Followup;
use common\models\Inquiry;
use common\models\User;
use common\widgets\Notification;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
$this->beginPage() ?>
<!DOCTYPE html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


    <?php $this->registerCssFile("@web/css/vendor/chosen.min.css"); ?>
    <?php $this->registerCssFile("@web/css/vendor/checkBo.min.css"); ?>
    <?php $this->registerCssFile("@web/css/summernote.css"); ?>
    <?php $this->registerCssFile("@web/css/vendor/sweetalert/sweet-alert.css"); ?>
    <?php $this->registerCssFile("@web/css/vendor/jquery.bootstrap-touchspin.min.css"); ?>
    <?php $this->registerCssFile("@web/css/bootstrap-datepicker3.css"); ?>
    <?php $this->registerCssFile("@web/css/clockpicker.min3f0d.css"); ?>
    <?php $this->registerCssFile("@web/css/font-awesome/font-awesome.min3f0d.css?v2.2.0"); ?>
</head>
<body class="page-loading">
<?= Notification::widget(); ?>
<?php $this->beginBody();
$currentUrl = Yii::$app->request->url;
$activeMenu='';$activeSubMenu='';$activeSubSubMenu = '';$activeSubSubMenu='';
switch($currentUrl){
    //DASHBOARD MENU STARTS
    case Yii::$app->homeUrl:
        $activeMenu = 'Dashboard';
        break;
    //DASHBOARD MENU ENDS
    //INQUIRY MENU STARTS
    case Yii::$app->getUrlManager()->createUrl(['/inquiry/index', 'type' => InquiryStatusTypes::IN_QUOTATION]):
        $activeMenu = 'Inquiry';
        $activeSubMenu = 'Pending Inquiry';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/inquiry/index', 'type' => InquiryStatusTypes::QUOTED]):
        $activeMenu = 'Inquiry';
        $activeSubMenu = 'Quoted Inquiry';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/inquiry/index', 'type' => InquiryStatusTypes::COMPLETED]):
        $activeMenu = 'Inquiry';
        $activeSubMenu = 'Completed Inquiry';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/inquiry/index', 'type' => InquiryStatusTypes::CANCELLED]):
        $activeMenu = 'Inquiry';
        $activeSubMenu = 'Cancelled Inquiry';
        break;
    //INQUIRY ENDS
    //FOLLOWUP STARTS
    case Yii::$app->getUrlManager()->createUrl(['/followup/index', 'status' => Followup::PENDING_FOLLOWUPS]):
        $activeMenu = 'Followups';
        $activeSubMenu = 'Pending Followups';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/followup/index', 'status' => Followup::OVERDUE_FOLLOWUPS]):
        $activeMenu = 'Followups';
        $activeSubMenu = 'Overdue Followups';
        break;
    //FOLLOWUP ENDS
    //CONFIGURATION STARTS
    case Yii::$app->getUrlManager()->createUrl(['/user/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Users';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/agent/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Agents';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/room-type/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Room Types';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/price-type/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Price Types';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/country/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Countries';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/city/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Cities';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/ip-restrictions/index']):
        $activeMenu = 'Configuration';
        $activeSubMenu = 'Ip Restrictions';
        break;
    //CONFIGURATION ENDS
    //REPORTS STARTS
    case Yii::$app->getUrlManager()->createUrl(['/report/day-performance']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Performance';
        $activeSubSubMenu = 'Day wise Count';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/report/staff-performance']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Performance';
        $activeSubSubMenu = 'Staff wise Count';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/report/day-login']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Login';
        $activeSubSubMenu = 'Day wise Login';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/report/staff-login']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Login';
        $activeSubSubMenu = 'Staff wise Login';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/report/booking-report']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Booking';
        break;
    case Yii::$app->getUrlManager()->createUrl(['/report/inquiry-report']):
        $activeMenu = 'Reports';
        $activeSubMenu = 'Inquiry';
    //REPORTS ENDS

}
//echo $activeMenu . $activeSubMenu;exit;
?>
<div class="pageload">
    <div class="pageload-inner">
        <div class="sk-rotating-plane"></div>
    </div>
</div>
<div class="app layout-fixed-header">
    <div class="sidebar-panel offscreen-left">
        <div class="brand">
            <div class="toggle-offscreen">
                <a href="javascript:;" class="visible-xs hamburger-icon" data-toggle="offscreen" data-move="ltr">
                    <span></span>
                    <span></span>
                </a>
            </div>
            <a class="brand-logo" href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index");?>">
                <span>Travel Portal</span>
            </a>
            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index");?>" class="small-menu-visible brand-logo">K</a>
        </div>
        <nav role="navigation">
            <ul class="nav">
                <li class="<?=$activeMenu=='Dashboard'?'active':'';?>">
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index");?>">
                        <i class="icon-compass"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("inquiry/create");?>">
                        <i class="icon-plus"></i>
                        <span>Add Inquiry</span>
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index"]);?>">
                        <i class="icon-list"></i>
                        <span>All Inquiries</span>
                    </a>
                </li>
                <?php }?>
            <?php //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER || Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF){?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index",'type' => InquiryStatusTypes::IN_QUOTATION]);?>">
                        <i class="icon-question"></i>
                        <span>Pending Inquiries</span>
                    </a>
                </li>
            <?php //} ?>
            <?php //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF || Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER || Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF){?>

                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index",'type' => InquiryStatusTypes::QUOTED]);?>">
                        <i class="icon-call-out"></i>
                        <span>Followups</span>
                    </a>
                </li>

                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["schedule-followup/index"]);?>">
                        <i class="icon-envelope"></i>
                        <span>Scheduled Mails</span>
                    </a>
                </li>
            <?php //} ?>
            <?php if(Yii::$app->user->identity->role==UserTypes::ADMIN  ){?>

                <!--<li>
                    <a href="<?php // Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::COMPLETED]);?>">
                        <i class="icon-plane"></i>
                        <span>Bookings</span>
                    </a>
                </li>-->
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::CANCELLED]);?>">
                        <i class="icon-close"></i>
                        <span>Cancelled Inquiries</span>
                    </a>
                </li>
            <?php } ?>

                <?php // if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF) {?>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::COMPLETED]);?>">
                            <i class="icon-plane"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                <?php // }?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::VOUCHERED]);?>">
                        <i class="icon-credit-card"></i>
                        <span>Vouchered</span>
                    </a>
                </li>
                <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("package/index");?>">
                        <i class="icon-bag"></i>
                        <span>Packages</span>
                    </a>
                </li>
                <?php }?>

                <?php if(Yii::$app->user->identity->role==UserTypes::ADMIN){?>
                    <li class="menu-accordion <?= $activeMenu=='Configuration'?'open':'';?>">
                        <a href="javascript:;">
                            <i class="icon-settings"></i>
                            <span>Configuration</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="<?= $activeSubMenu=='Users'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("user/index");?>">
                                    <i class="icon-user"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Agents'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("agent/index");?>">
                                    <i class="icon-user-follow"></i>
                                    <span>Agents</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Room Types'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("room-type/index");?>">
                                    <i class="icon-home"></i>
                                    <span>Room Types</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Price Types'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("price-type/index");?>">
                                    <i class="fa-money awesome-icon"></i>
                                    <span>Price Types</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Countries'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("country/index");?>">
                                    <i class="icon-pointer"></i>
                                    <span>Countries</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Cities'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("city/index");?>">
                                    <i class="icon-map"></i>
                                    <span>Cities</span>
                                </a>
                            </li>
                            <?php if(Yii::$app->user->identity->role==UserTypes::ADMIN) { ?>
                            <li class="<?= $activeSubMenu=='Ip Restrictions'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("ip-restrictions/index");?>">
                                    <i class="icon-map"></i>
                                    <span>Ip Restrictions</span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="menu-accordion <?= $activeMenu=='Reports'?'open':'';?>">
                        <a href="javascript:;">
                            <i class="icon-docs"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="sub-menu">
                            <li class="menu-accordion <?= $activeSubMenu=='Performance'?'open':'';?>">
                                <a href="javascript:;">
                                    <span>Performance</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="<?= $activeSubSubMenu=='Day wise Count'?'open':'';?>">
                                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/day-performance");?>">
                                            <span>Day wise</span>
                                        </a>
                                    </li>
                                    <li class="<?= $activeSubSubMenu=='Staff wise Count'?'open':'';?>">
                                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/staff-performance");?>">
                                            <span>Staff wise</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-accordion <?= $activeSubMenu=='Login'?'open':'';?>">
                                <a href="javascript:;">
                                    <span>Login</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="<?= $activeSubSubMenu=='Day wise Login'?'open':'';?>">
                                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/day-login");?>">
                                            <span>Day wise</span>
                                        </a>
                                    </li>
                                    <li class="<?= $activeSubSubMenu=='Staff wise Login'?'open':'';?>">
                                        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/staff-login");?>">
                                            <span>Staff wise</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="<?= $activeSubMenu=='Booking'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/booking-report");?>">
                                    <span>Booking</span>
                                </a>
                            </li>
                            <li class="<?= $activeSubMenu=='Inquiry'?'open':'';?>">
                                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("report/inquiry-report");?>">
                                    <span>Inquiry</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <br/>
                    <br/>
                    <br/>
                <?php } ?>
            </ul>
        </nav>
    </div>
    <div class="main-panel">
        <div class="header navbar">
            <div class="brand visible-xs">
                <div class="toggle-offscreen">
                    <a href="javascript:;" class="hamburger-icon visible-xs" data-toggle="offscreen" data-move="ltr">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
                <a class="brand-logo">
                    <span>Travel Portal</span>
                </a>

            </div>
            <ul class="nav navbar-nav hidden-xs">
                <li>
                    <a href="javascript:;" class="small-sidebar-toggle ripple" data-toggle="layout-small-menu">
                        <i class="icon-toggle-sidebar"></i>
                    </a>
                </li>

                <li class="searchbox">
                    <a href="javascript:;" data-toggle="search">
                        <i class="search-close-icon icon-close hide"></i>
                    </a>
                </li>
            </ul>
            <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
            <ul class="nav navbar-nav hidden-xs">
                <li >
                      <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/create"]); ?>" ><span class="btn-lg  icon-plus"></span>
                     <span style="font-size:18px">Add Inquiry</span> 
                    </a>
                </li>
            </ul>
            <?php }?>
            <?php
                 $pending_inquiries_alert=[];  $quoted_inquiries_alert=[];
                  if(Yii::$app->user->identity->role==UserTypes::ADMIN){
                        $pending_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::IN_QUOTATION])->andWhere(['<=', 'created_at', strtotime('-3 days', strtotime('today'))])->all();
                        $quoted_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'from_date', strtotime('+5 days', strtotime('today'))])->all();
                  }
                  if(Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER){
                      $pending_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::IN_QUOTATION, 'quotation_manager'=>Yii::$app->user->identity->id])->andWhere(['<=', 'created_at', strtotime('-3 days', strtotime('today'))])->all();
                  }
                  if(Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF){
                      $pending_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::IN_QUOTATION, 'quotation_staff'=>Yii::$app->user->identity->id])->andWhere(['<=', 'created_at', strtotime('-3 days', strtotime('today'))])->all();
                  }
                  if(Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER){
                      $quoted_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::QUOTED, 'follow_up_head'=>Yii::$app->user->identity->id])->andWhere(['<=', 'from_date', strtotime('+5 days', strtotime('today'))])->all();
                  }
                  if(Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF){
                      $quoted_inquiries_alert = Inquiry::find()->where(['status' => InquiryStatusTypes::QUOTED, 'follow_up_staff'=>Yii::$app->user->identity->id])->andWhere(['<=', 'from_date', strtotime('+5 days', strtotime('today'))])->all();
                  }
            ?>
            <ul class="nav navbar-nav navbar-right">
                <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
                <li class="notification-bell">
                    <a href="javascript:;" class="ripple" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell"></i>
                        <span class="badge bg-danger up"><?=count($pending_inquiries_alert)+count($quoted_inquiries_alert)?></span>
                    </a>

                    <ul class="dropdown-menu notifications">
                        <li class="notifications-header">
                            <p class="error-hint small">You have <?=count($pending_inquiries_alert)+count($quoted_inquiries_alert)?> notifications</p>
                        </li>
                        <li>
                            <ul class="notifications-list">
                                <div class="scrollable" style="height: 400px; width:100%">
                                <?php foreach($quoted_inquiries_alert as $alert):?>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="notification-icon">
                                                <div class="circle-icon bg-danger text-white">
                                                    <i class="icon-call-out"></i>
                                                </div>
                                            </div>
                                            <span class="notification-message"> Inquiry from <?=$alert->name?> is still not completed.</span>
                                        </a>
                                    </li>
                                    <?php endforeach ?>

                                    <?php foreach($pending_inquiries_alert as $alert):?>
                                        <li>
                                            <a href="javascript:;">
                                                <div class="notification-icon">
                                                    <div class="circle-icon bg-warning text-white">
                                                        <i class="icon-question"></i>
                                                    </div>
                                                </div>
                                                <span class="notification-message"> Inquiry from <?=$alert->name?> is still pending.</span>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </div>
                            </ul>
                        </li>
                    </ul>

                </li>
                <?php }?>
                <li id="pass">
                    <a href="javascript:;"  data-toggle="dropdown">
                        <?php $model = User::findOne(Yii::$app->user->identity->id)?>
                        <?php if (Yii::$app->user->identity->role == UserTypes::ADMIN) {?>
                            <img src="<?= isset($model->media_id) ? DirectoryTypes::getAdminDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?>"
                                 alt="user" class="header-avatar img-circle">
                        <?php }?>
                        <?php if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {?>
                            <img src="<?= isset($model->media_id) ? DirectoryTypes::getQuotationManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?>"
                                 alt="user" class="header-avatar img-circle">
                        <?php }?>
                        <?php if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {?>
                            <img src="<?= isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?>"
                                 alt="user" class="header-avatar img-circle">
                        <?php }  else if($model->role==UserTypes::QUOTATION_STAFF || $model->role==UserTypes::FOLLOW_UP_STAFF || $model->role==UserTypes::BOOKING_STAFF){?>
                            <img
                                src="<?php echo isset($model->media_id) ? DirectoryTypes::getStaffDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                class="header-avatar img-circle" />
                        <?php }?>
                        <span><?= Yii::$app->user->identity->username?></span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?= Html::a('Edit Profile', ['user/edit-profile'], ['data-method' => 'post']) ?>
                        </li>
                        <li>
                            <?= Html::a('Change Password', ['user/change-password'], ['data-method' => 'post']) ?>
                        </li>
                        <li>
                            <?= Html::a('Logout', ['site/logout'], ['data-method' => 'post']) ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <?= $content ?>
        </div>
    </div>
    <footer class="content-footer">
        <nav class="footer-right">
            <!--	<ul class="nav">
                    <li>
                        <a href="javascript:;">Feedback</a>
                    </li>
                    <li>
                        <a href="javascript:;" class="scroll-up">
                            <i class="fa fa-angle-up"></i>
                        </a>
                    </li>
                </ul>-->
        </nav>
        <nav class="footer-left hidden-xs">
            <!--	<ul class="nav">
                    <li>
                        <a href="javascript:;">Privacy</a>
                    </li>
                    <li>
                        <a href="javascript:;">Terms</a>
                    </li>
                    <li>
                        <a href="javascript:;">Help</a>
                    </li>
                </ul>-->
        </nav>
    </footer>
</div>
<?php $this->registerJsFile('@web/js/app.min.js'); ?>
<?php $this->registerJsFile('@web/js/summernote/summernote.min.js', ['depends' => [\yii\web\JqueryAsset::className(), yii\bootstrap\BootstrapAsset::className(),\yii\bootstrap\BootstrapPluginAsset::className()]]); ?>
<?php $this->registerJsFile('@web/js/summernote/wysiwyg.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/vendor/sweetalert/sweet-alert.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/ui/alert.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/helpers/classie.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/helpers/inputfx.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/helpers/selectfx.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/forms/bootstrap-datepicker.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/forms/bootstrap-clockpicker.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>
<?php $this->registerJsFile('@web/js/jquery.inputmask.bundle.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);
$this->registerJsFile('@web/js/jquery.inputmask-multi.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]); ?>

<?php $this->registerJsFile('@web/js/vendor/chosen.jquery.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php $this->registerJsFile('@web/js/vendor/checkBo.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php $this->registerJsFile('@web/js/vendor/jquery.bootstrap-touchspin.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php $this->registerJsFile('@web/js/forms/plugins.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>

<?php $this->registerJsFile('@web/js/jquery.noty.packaged.min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php $this->registerJsFile('@web/js/helpers/noty-defaults.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php $this->registerJsFile('@web/js/ui/notifications.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>
<?php //$this->registerJsFile('@web/js/jquery.matchHeight-min.js', ['depends' => [\yii\web\JqueryAsset::className(),]]);?>

<?php $this->registerJs('
 $("document").ready(function(){
 $(".notification-bell").on("click",function(){
    if($(this).hasClass("open")){
        $(this).removeClass("open");
    }
    else{
        $(this).addClass("open");
    }
 });
 $(".app-noty-top-right").fadeOut(3000);
 $(".app-noty-top-right").on("click", function () {
    $(this).fadeOut(1000);
});

$("li#pass").on("click",function(){
    $(this).toggleClass("open");
});

});

'); ?>

<?php $this->endBody() ?>

<?php $this->endPage() ?>
