<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 23-06-2016
 * Time: 19:50
 */
use backend\models\enums\DirectoryTypes;
use backend\models\enums\UserTypes;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Edit Profile: ' . $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="page-title">
        <div class="title">Users</div>
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
        <div class="card-header bg-danger-dark">
            <strong class="text-white">User Details</strong>
        </div>
        <div class="card-block">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ]
            ]); ?>
            <div class="form-group row">
                <div class="col-sm-6">

                    <?php if ($model->role == UserTypes::ADMIN) { ?>
                        <img
                            src="<?php echo isset($model->media_id) ? DirectoryTypes::getAdminDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                            class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                            alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                    <?php }
                     if ($model->role == UserTypes::INQUIRY_HEAD) { ?>
                        <img
                            src="<?php echo isset($model->media_id) ? DirectoryTypes::getInquiryHeadDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                            class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                            alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                    <?php }
                    if ($model->role == UserTypes::QUOTATION_MANAGER) { ?>
                        <img
                            src="<?php echo isset($model->media_id) ? DirectoryTypes::getQuotationManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                            class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                            alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                    <?php }
                    if ($model->role == UserTypes::FOLLOW_UP_MANAGER) { ?>
                        <img
                            src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                            class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                            alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                    <?php }  else if ($model->role == UserTypes::FOLLOW_UP_STAFF || $model->role == UserTypes::QUOTATION_STAFF || $model->role == UserTypes::BOOKING_STAFF){?>
                        <img
                            src="<?php echo isset($model->media_id) ? DirectoryTypes::getStaffDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                            class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                            alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                    <?php } ?>
                    <?= $form->field($model, 'media_id')->fileInput()->label(false); ?>
                </div>
                <div class="col-sm-6">
                    <?= Html::a('Change Password', ['change-password'], ['class' => 'btn btn-primary btn-round btn-sm pull-right']) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <?= $form->field($model, 'username')->textInput() ?>
                </div>

                <div class="col-sm-4">
                    <?= $form->field($model, 'email')->textInput() ?>
                </div>

                <div class="col-sm-4">
                    <?= $form->field($model, 'mobile')->textInput() ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label">Signature</label>
                    <?= $form->field($model, 'signature')->textarea(['class' => 'summernote'])->label(false) ?>
                </div>
            </div>

            <div class="form-group row">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-round pull-right']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

<?php

$this->registerJs('
 $(document).ready(function(){
    $(".dropdown-toggle").remove();
    $(".btn-codeview").remove();
     function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $("#profile-photo").attr("src", e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
       }
      }
      $("#user-media_id").innerHTML = "";
      $("#user-media_id").change(function(){
          readURL(this);
      });
     /* $("#user-media_id").click(function() {
           $("input[id=\'profile-photo\']");
      });*/
       $("#user-media_id").hide();
  });
  ');
