<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RecordLoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booking Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Booking Report</div>
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
<?php echo $this->render('_booking_search', [
        'model' => $searchModel,
        'year' => $year,
        'user' => $user,
    ])?>
    <div class="card bg-white">
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $provider,
                //'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'columns' => [
                    'staff',
                    'jan',
                    'feb',
                    'mar',
                    'apr',
                    'may',
                    'jun',
                    'jul',
                    'aug',
                    'sep',
                    'oct',
                    'nov',
                    'dec',
                ],
            ]); ?>
        </div>
    </div>

</div>
