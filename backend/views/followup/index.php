<?php


use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;

use backend\models\enums\UserTypes;
use common\models\Followup;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel_in_quotation common\models\InquirySearch */
/* @var $searchModel_quoted common\models\InquirySearch */
/* @var $searchModel_in_follow_up common\models\InquirySearch */
/* @var $searchModel_amended common\models\InquirySearch */
/* @var $searchModel_completed common\models\InquirySearch */
/* @var $searchModel_cancelled common\models\InquirySearch */
/* @var $dataProvider_in_quotation yii\data\ActiveDataProvider */
/* @var $dataProvider_quoted yii\data\ActiveDataProvider */
/* @var $dataProvider_in_follow_up yii\data\ActiveDataProvider */
/* @var $dataProvider_amended yii\data\ActiveDataProvider */
/* @var $dataProvider_completed yii\data\ActiveDataProvider */
/* @var $dataProvider_cancelled yii\data\ActiveDataProvider */

$this->title = $status==Followup::OVERDUE_FOLLOWUPS ? 'Overdue Follow Up List' : 'Pending Follow Up List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title"><?=$this->title?></div>
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

<?php if(count($model)>0)
        {   if($status==Followup::PENDING_FOLLOWUPS)
                echo $this->render('_search', ['model' => $searchModel]);
        }?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,

            'itemView' => function ($model) {
                return $this->render('_follow_up_index', ['model' => $model]);
            },
           'layout' => '{summary}{pager}

                            <div class="card-block" id="parent">
                            {items}
                            </div>

                        '
        ]);
        ?>

<div class="modal bs-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change Status Of Inquiry</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group form-material">

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="control-label">Status</label>
                            <?= $form->field($inquiry_new_model, 'status')->dropDownList(InquiryStatusTypes::$titles,['id' => 'status', 'prompt' => 'Select Status'])->label(false); ?>
                        </div>
                        <div id="followupdate" class="col-sm-6" style="display: none;">
                            <?= $form->field($followup_model, 'date')->textInput([ 'class' => 'followupdate form-control', 'name' => 'Followup[date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label">Notes</label>
                            <?= $form->field($inquiry_new_model, 'notes')->textarea(['rows' => 6,'class'=>'summernote'])->label(false) ?>
                        </div>
                    </div>
                    <?= Html::hiddenInput('inquiry_id', null, ['id' => 'inquiry_id']); ?>

                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right', 'id' => 'update_status']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJs('


$(\'.edit\').on(\'click\', function () {
            var id = $(this).data(\'id\');
           // alert(id);
            $(\'#inquiry_id\').val(id);
        });

         $("#status").change(function(){

    var status=$(\'#status\').children(\'option\').filter(\':selected\').text();

    if(status==\'Follow Up\')
    {
    $(\'#followupdate\').show();

    }else
    {
    $(\'#followupdate\').hide();
    }
    });





'); ?>






