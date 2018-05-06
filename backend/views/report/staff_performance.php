<?php

use backend\models\enums\UserTypes;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ActivityCountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staff Wise Performance Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Staff Wise Performance Report</div>
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
<div class="report">
    <?php echo $this->render('_staff_performance_search', [
        'model' => $searchModel,
        's_date' => $s_date,
        'e_date' => $e_date,
        'user' => $user,
    ])?>
    <div class="card bg-white">
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'columns' => [
                    'date:date',
                    [
                        'attribute' => 'user_id',
                        'value' => function($data){
                            return $data->user->username;
                        }
                    ],
                    [
                        'attribute' => 'user.role',
                        'label' => 'Role',
                        'value' => function($data){
                            return UserTypes::$headers[$data->user->role];
                        }
                    ],
                    [
                        'attribute' => 'followup_count',
                        'label' => 'Performance',
                        'format' => 'raw',
                        'value' => function($data){
                            if($data->user->role==UserTypes::FOLLOW_UP_MANAGER || $data->user->role==UserTypes::FOLLOW_UP_STAFF)
                                return 'Followup Taken: '. $data->followup_count ;
                            else if($data->user->role==UserTypes::QUOTATION_STAFF || $data->user->role==UserTypes::QUOTATION_MANAGER)
                                return 'Quotation Sent: ' . $data->quotation_count;
                            else if($data->user->role==UserTypes::ADMIN)
                                return 'Quotation Sent: ' . $data->quotation_count . '<br/>' . 'Followup Taken: '. $data->followup_count ;

                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
