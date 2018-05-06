<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RoomTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Room Types';
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
    $status = [
        ['id' => 10, 'name' => 'Active'],
        ['id' => 0, 'name' => 'Inactive'],
    ];
    ?>
    <div class="card bg-white">
        <div class="card-header">
            <?= Html::a('Add Room Type', ['create'], ['class' => 'btn btn-primary btn-round']) ?>
        </div>

        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'columns' => [
                    'type',
                    [
                        'format' => 'raw',
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == 10) {
                                return '<span class="label label-lg label-success">Active</span>';
                            } else {
                                return '<span class="label label-lg label-danger">Inactive</span>';
                            }
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map($status, 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Select Status']),
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
                        'buttons' => [
                            'delete' => function($url, $model) {
                                return $model->status==10 ? Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                    'title' => Yii::t('yii', 'Deactivate'),
                                    'class' => 'swal-warning-confirm',
                                    'data' => [
                                        'url' => Yii::$app->getUrlManager()->createUrl(['/room-type/delete', 'id' => $model->id])
                                    ],
                                ]): '';
                            }
                        ],
                    ]
                ],
            ]); ?>
        </div>
    </div>

</div>
