<?php

use common\models\ScheduleFollowup;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ScheduleFollowupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Schedule Followups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Room Types</div>
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
<div class="agent-index">
    <?php
    $is_sent = [
        ['id' => 1, 'name' => 'Sent'],
        ['id' => 0, 'name' => 'Not Sent'],
    ];
    ?>
    <div class="card bg-white">
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
               // 'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'custom-table table table-hover table-condensed responsive m-b-0',
                ],
                'columns' => [
                    [
                        'attribute' => 'passenger_email',
                        'contentOptions' => ['class' => 'card-custom-height'],
                    ],
                    [
                        'attribute' => 'scheduled_at',
                        'value' => function($data){
                            return Yii::$app->formatter->asDatetime($data->scheduled_at);
                        },
                        'contentOptions' => ['class' => 'card-custom-height'],
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'is_sent',
                        'label' => 'Status',
                        'contentOptions' => ['class' => 'card-custom-height'],
                        'value' => function ($model) {
                            if ($model->is_sent == 1) {
                                return '<span class="label label-lg label-success">Sent</span>';
                            } else if($model->is_sent == 0 && $model->status == ScheduleFollowup::STATUS_ACTIVE) {
                                return '<span class="label label-lg label-warning">Pending</span>';
                            }
                            else if($model->is_sent == 0 && $model->status == ScheduleFollowup::STATUS_DELETED) {
                                return '<span class="label label-lg label-danger">Cancelled</span>';
                            }
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map($is_sent, 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Select Status']),
                    ]
                ],
            ]); ?>
        </div>
    </div>

</div>
<?php
$this->registerJs("
    $(document).ready(function(){
		$('.card-custom-height').click(function (e) {
            var id = $(this).closest('tr').data('key');
            if(e.target == this)
                location.href = '" . Url::to(['schedule-followup/view']) . "&id=' + id;
        });
    });
");
