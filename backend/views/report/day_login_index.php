<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RecordLoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Day Wise Login Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Day Wise Login Report</div>
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
    <?php echo $this->render('_day_login_search', [
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
                    [
                        'attribute' => 'user_id',
                        'value' => function($data){
                            return $data->user->username;
                        }
                    ],
                   /* [
                        'attribute' => 'login_time',
                        'value' => function($data){
                            return Yii::$app->formatter->asDatetime($data->login_time);
                        }
                    ],*/
                    'login_time:datetime',
                ],
            ]); ?>
        </div>
    </div>

</div>
