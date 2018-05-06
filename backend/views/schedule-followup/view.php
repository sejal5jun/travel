<?php

use common\models\ScheduleFollowup;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ScheduleFollowup */

$this->title = $model->passenger_email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schedule Followups'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("schedule-followup/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="page-title">
        <div class="title">Scheduled Followups</div>
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
            <?php if($model->is_sent == 0 && $model->status == ScheduleFollowup::STATUS_ACTIVE){?>
                <div class="card-header">
                    <?= Html::a(Yii::t('app', 'Update'),'javascript:void(0);', ['class' => 'btn btn-primary btn-round btn-update',]);?>
                    <?php if($model->status == 10){
                        echo Html::a(Yii::t('app', 'Delete'),'#', [
                            'class' => 'btn btn-danger btn-round swal-warning-schedule-cancel',
                            'data' => [
                                'url' => Yii::$app->getUrlManager()->createUrl(['/schedule-followup/delete', 'id' => $model->id])
                            ],
                        ]);
                    }?>
                </div>
            <?php } ?>
            <div class="card-block">
                <div class="update-div" style="display: none;">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ]
                    ]); ?>
                    <div class="form-group row">
                        <div id="schedule_date" class="col-sm-4">
                            <label class="control-label">Date</label>
                            <?=Html::textInput('schedule_date',date('Y-m-d', $model->scheduled_at),['class' => 'form-control', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d'])?>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">Time</label>
                            <?=Html::textInput('schedule_time',date('h:i a',$model->scheduled_at),['class' => 'form-control', 'id' => "tm"])?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label">Body</label>
                            <?= $form->field($model, 'text_body')->textarea(['rows' => 6, 'class' => 'summernote'])->label(false) ?>
                        </div>
                    </div>


                    <div class="form-group row">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary btn-round pull-right btn-lg btn-sub']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <div class="view-div">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Passenger Email:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->passenger_email ?></label>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Scheduled At:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?=Yii::$app->formatter->asDatetime($model->scheduled_at)?></label>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Scheduled By:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->scheduledBy->username?></label>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label model-view-label">
                                <strong>Status:</strong>
                            </label><br/>
                            <label class="control-label model-view">
                                <?php
                                  if($model->is_sent==1)
                                    echo "Sent" ;
                                  else{
                                      if($model->status == ScheduleFollowup::STATUS_DELETED)
                                          echo "Cancelled";
                                      else
                                          echo "Pending";
                                  }  ?>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label model-view-label">
                                <strong>Mail Body:</strong>
                            </label><br/>
                            <label class="control-label model-view"><?= $model->text_body ?></label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php

$this->registerJs('
 $(document).ready(function(){
 $("#tm").clockpicker({
        autoclose: true,
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