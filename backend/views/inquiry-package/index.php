<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InquiryPackageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inquiry Packages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-package-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Inquiry Package', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'inquiry_id',
            'package_id',
            'date_of_travel',
            'length_of_stay',
            // 'guest_count',
            // 'rooms',
            // 'check_in',
            // 'check_out',
            // 'itinerary:ntext',
            // 'pricing',
            // 'terms_and_conditions:ntext',
            // 'other_info:ntext',
            // 'status',
            'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
