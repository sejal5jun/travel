<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\IpRestrictionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ip Restrictions';
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

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="card bg-white">
    <div class="card-block">
        <p>
            <?= Html::a('Add Ip Restrictions', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'custom-table table table-hover table-condensed responsive m-b-0',
        ],
        'columns' => [
        
            'ip',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>