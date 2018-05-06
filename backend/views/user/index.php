<?php

use backend\models\enums\UserTypes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Users</div>
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
<?php
$status = [
    ['id' => 10, 'name' => 'Active'],
    ['id' => 0, 'name' => 'Inactive'],
];

$role = [
    ['id' => UserTypes::ADMIN, 'name' => 'Admin'],
    ['id' => UserTypes::INQUIRY_HEAD, 'name' => 'Inquiry Head'],
    ['id' => UserTypes::QUOTATION_MANAGER, 'name' => 'Quotation Manager'],
    ['id' => UserTypes::FOLLOW_UP_MANAGER, 'name' => 'Follow Up Manager'],
    ['id' => UserTypes::QUOTATION_STAFF, 'name' => 'Quotation Staff'],
    ['id' => UserTypes::FOLLOW_UP_STAFF, 'name' => 'Follow Up Staff'],
    ['id' => UserTypes::BOOKING_STAFF, 'name' => 'Booking Staff'],
];
?>
<?php echo $this->render('_search', [
    'model' => $searchModel,
])?>
<div class="user-index">


    <div class="card bg-white">
        <div class="card-header">
            <?= Html::a('Add User', ['create'], ['class' => 'btn btn-primary btn-round']) ?>
        </div>

        <div class="card-block">
            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'columns' => [
                'username',
                //'email:email',
                [
                    'attribute' => 'Role',
                    'value' => function ($data) {
                        return UserTypes::$headers[$data->role];
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'role', ArrayHelper::map($role, 'id', 'name'),['class'=>'form-control','prompt' => 'Select Role']),
                ],

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
                    'template' => '{view}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            return $model->status==10 ? Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', [
                                'title' => Yii::t('yii', 'Deactivate'),
                                'class' => 'swal-warning-confirm',
                                'data' => [
                                    'url' => Yii::$app->getUrlManager()->createUrl(['/user/delete', 'id' => $model->id])
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
