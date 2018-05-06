<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RecordLoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inquiry Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Inquiry Report</div>
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
    <?php echo $this->render('_inquiry_search', [
        'model' => $searchModel,
        's_date' => $s_date,
        'e_date' => $e_date,
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
                        'attribute' => 'new_inquiry_count',
                        'label' => 'Inquiry Added',
                        'value' => function($data){
                            return $data->new_inquiry_count;
                        }
                    ],
                    [
                        'attribute' => 'quotation_count',
                        'label' => 'Quotation Sent',
                        'value' => function($data){
                            return $data->quotation_count;
                        }
                    ],
                    [
                        'attribute' => 'followup_count',
                        'label' => 'Followups Taken',
                        'value' => function($data){
                            return $data->followup_count;
                        }
                    ],
                    [
                        'attribute' => 'booking_count',
                        'label' => 'Bookings',
                        'value' => function($data){
                            return $data->booking_count;
                        }
                    ],
                    [
                        'attribute' => 'cancellation_count',
                        'label' => 'Cancelled Inquiries',
                        'value' => function($data){
                            return $data->cancellation_count;
                        }
                    ],
                    [
                        'attribute' => 'date',
                        'label' => 'Date',
                        'value' => function($data){
                            return date("M-d-Y", $data->date);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>
