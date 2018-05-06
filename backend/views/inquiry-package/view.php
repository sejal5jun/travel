<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InquiryPackage */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inquiry Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-package-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'inquiry_id',
            'package_id',
            'date_of_travel',
            'length_of_stay',
            'guest_count',
            'rooms',
            'check_in',
            'check_out',
            'itinerary:ntext',
            'pricing',
            'terms_and_conditions:ntext',
            'other_info:ntext',
            'status',
            'created_at:datetime'
        ],
    ]) ?>

</div>
