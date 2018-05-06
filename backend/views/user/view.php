<?php

use backend\models\enums\DirectoryTypes;
use backend\models\enums\UserTypes;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("user/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Users</div>
    <div class="sub-title"><?= Html::encode($this->title) ?></div>
</div>
<ol class="breadcrumb">
    <li>
        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index");?>">Dashboard</a>
    </li>
    <?php foreach($this->params['breadcrumbs'] as $k=>$v){
        if(isset($v['label'])){
            echo "<li><a href=".$v['url'][0].">".$v['label']."</a></li>";
        }else{
            echo "<li class='active ng-binding'>$v</li>";
        }
    }?>
</ol>
<div class="user-view">
    <div class="card bg-white">
        <div class="card-header">
            <?= Html::a(Yii::t('app', 'Update'),'javascript:void(0);', ['class' => 'btn btn-primary btn-round btn-update',]);?>
            <?php if($model->status == 10){
             echo Html::a(Yii::t('app', 'Delete'),'#', [
                'class' => 'btn btn-danger btn-round swal-warning-confirm',
                'data' => [
                    'url' => Yii::$app->getUrlManager()->createUrl(['/user/delete', 'id' => $model->id])
                ],
            ]);
            }else
            {
                echo Html::a(Yii::t('app', 'Activate'),'#', [
                    'class' => 'btn btn-success swal-activate btn-round ',
                    'data' => [
                        'url' => Yii::$app->getUrlManager()->createUrl(['/user/delete', 'id' => $model->id])
                    ],
                ]);
            }?>
        </div>

        <div class="card-block">
            <div class="update-div" style="display: none;">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ]
                    ]); ?>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <?php if($model->role==UserTypes::ADMIN){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getAdminDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                            <?php } else if($model->role==UserTypes::INQUIRY_HEAD){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getInquiryHeadDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>

                            <?php } else if($model->role==UserTypes::QUOTATION_MANAGER){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getQuotationManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>

                            <?php } else if($model->role==UserTypes::FOLLOW_UP_MANAGER){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                            <?php }else if($model->role==UserTypes::QUOTATION_STAFF || $model->role==UserTypes::FOLLOW_UP_STAFF || $model->role==UserTypes::BOOKING_STAFF){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getStaffDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                            <?php } else {?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image" onclick="document.getElementById('user-media_id').click()"/>
                            <?php } ?>
                            <?= $form->field($model, 'media_id')->fileInput()->label(false); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $form->field($model, 'email')->textInput() ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $form->field($model, 'mobile')->textInput() ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <?= $form->field($model, 'username')->textInput(); ?>
                        </div>
                        <div class="col-sm-2">
                            <br/>
                            <label class="cb-checkbox cb-md" id="check-label">
                                <span class="cb-inner"><i><input type="checkbox" name="update-password" value="checked-checkbox-val" id="password-check"></i></span>Update Password
                            </label>
                        </div>

                       <!-- <div class="col-sm-3">
                            <br/>
                            <input type="checkbox" name="update-password" id="password-check">Update Password
                        </div>-->
                        <div class="col-sm-3 pass-div" style="display: none">
                            <?= $form->field($model, 'new_password')->passwordInput(['value' => ''])->label("New Password"); ?>
                            <div class="error-hint pass-error" style="display: none">New Password can not be blank.</div>
                        </div>
                        <div class="col-sm-3 pass-div" style="display: none">
                            <?= $form->field($model, 'confirm_password')->passwordInput(['value' => '']); ?>
                            <div class="error-hint confirm-error" style="display: none">Confirm Password can not be blank.</div>
                        </div>
                        <div class="col-sm-1 pass-div" style="display: none">
                            <p style="margin-top:30px; ">
                                <i id="show" class="fa-lg fa-eye"></i>
                            </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label class="control-label">Role</label>
                            <?= $form->field($model, 'role')->dropDownList(UserTypes::$headers, [$model->role => ['Selected' => 'selected']])->label(false) ?>
                        </div>
                       <!-- <div class="col-sm-3 quotation-div" style="display: none;">
                            <label class="control-label">Head</label><br/>
                            <?php //Html::dropDownList('quotation_head',$model->head, User::getQuotationManagers(),['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-3 followup-div" style="display: none;">
                            <label class="control-label">Head</label><br/>
                            <?php //Html::dropDownList('followup_head',$model->head, User::getFollowupManagers(),['class' => 'form-control'])?>
                        </div>
                        <div class="col-sm-3 admin-div" style="display: none;">
                            <label class="control-label">Head</label><br/>
                            <?php //Html::dropDownList('head',$model->head, User::getAdmins(),['class' => 'form-control'])?>
                        </div>-->
                        <div class="col-sm-6">
                            <label class="control-label">Signature</label>
                            <?= $form->field($model, 'signature')->textarea(['class' => 'summernote'])->label(false) ?>
                        </div>
                    </div>


                <div class="form-group row">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-round pull-right btn-lg btn-sub']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            <div class="view-div">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <?php if($model->role==UserTypes::ADMIN){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getAdminDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>
                            <?php } else if($model->role==UserTypes::INQUIRY_HEAD){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getInquiryHeadDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>
                            <?php } else if($model->role==UserTypes::QUOTATION_MANAGER){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getQuotationManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>

                            <?php } else if($model->role==UserTypes::FOLLOW_UP_MANAGER){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>
                            <?php }else if($model->role==UserTypes::QUOTATION_STAFF || $model->role==UserTypes::FOLLOW_UP_STAFF || $model->role==UserTypes::BOOKING_STAFF){?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getStaffDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>
                            <?php } else {?>
                                <img
                                    src="<?php echo isset($model->media_id) ? DirectoryTypes::getFollowUpManagerDirectory(true) . $model->media->file_name : Url::to('@web/images/profile_pic.png', true); ?> "
                                    class="profile-photo image responsive" id="profile-photo" width="100" height="100"
                                    alt="Profile Image"/>
                            <?php } ?>

                        </div>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                  <strong>Email:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->email ?></label>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Mobile:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->mobile ?></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Username:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->username ?></label>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Role:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= UserTypes::$headers[$model->role] ?></label>
                        </div>
                        <?php if($model->head!=''){?>
                       <!-- <div class="col-sm-4">
                            <label class="control-label model-view-label">
                                <strong>Head:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?php // $model->head0->username?></label>
                        </div>-->
                        <?php } ?>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="control-label model-view-label">
                                <strong>Signature:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->signature?></label>
                        </div>
                    </div>

                </div>
        </div>

    </div>
</div>
<?php

$this->registerJs('
 $(document).ready(function(){
      $(".dropdown-toggle").remove();
      $(".btn-codeview").remove();
      $("#user-media_id").innerHTML = "";
      $("#user-media_id").change(function(){
          readURL(this);
      });

      $("#user-media_id").hide();

      function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                  $("#profile-photo").attr("src", e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
      }

        var role = $("#user-role").val();
      if(role == ' . UserTypes::QUOTATION_STAFF . '){
          $(".quotation-div").show();
          $(".followup-div").hide();
          $(".admin-div").hide();
      }
      else if(role == ' . UserTypes::FOLLOW_UP_STAFF . '){
          $(".followup-div").show();
          $(".quotation-div").hide();
          $(".admin-div").hide();
      }
      else if(role == ' . UserTypes::BOOKING_STAFF . '){
          $(".admin-div").show();
          $(".quotation-div").hide();
          $(".followup-div").hide();
      }
       else{
          $(".admin-div").hide();
          $(".quotation-div").hide();
          $(".followup-div").hide();
       }

       $("#user-role").change(function(){
            var role = $(this).val();
             if(role == ' . UserTypes::QUOTATION_STAFF . '){
                  $(".quotation-div").show();
                  $(".followup-div").hide();
                  $(".admin-div").hide();
              }
              else if(role == ' . UserTypes::FOLLOW_UP_STAFF . '){
                  $(".followup-div").show();
                  $(".quotation-div").hide();
                  $(".admin-div").hide();
              }
              else if(role == ' . UserTypes::BOOKING_STAFF . '){
                  $(".admin-div").show();
                  $(".quotation-div").hide();
                  $(".followup-div").hide();
              }
              else{
                  $(".admin-div").hide();
                  $(".quotation-div").hide();
                  $(".followup-div").hide();
              }
       });

       $("#password-check").click(function(){

            if($(this).prop("checked") == true){
                $(".pass-div").show();
                $("#check-label").addClass("checked");
            }
            else if($(this).prop("checked") == false){
                $(".pass-div").hide();
                $("#check-label").removeClass("checked");
            }
        });
$("#show").click(function(){
if($(this).attr("class") == "fa-lg fa-eye"){
$("#user-new_password").attr("type","text");
$("#user-confirm_password").attr("type","text");
$(this).removeClass("fa-eye");
$(this).addClass("fa-eye-slash");
}else
{
$("#user-new_password").attr("type","password");
$("#user-confirm_password").attr("type","password");
$(this).removeClass("fa-eye-slash");
$(this).addClass("fa-eye");
}
});
        $(".btn-sub").click(function(){
                if($("#password-check").prop("checked") == true){

                    var confirm_pass = $("#user-confirm_password").val();
                    var password = $("#user-password_hash").val();
                    if(confirm_pass=="" || password==""){
                        if(confirm_pass==""){
                             $(".confirm-error").show();
                        }
                        if(password==""){
                            $(".pass-error").show();
                        }
                        return false;
                    }
                    else{
                        $(".pass-error").hide();
                        $(".confirm-error").hide();
                        return true;
                    }
                }
                else{
                    $(".confirm-error").hide();
                    $(".pass-error").hide();
                    return true;
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

 });
 ');