<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\IpRestrictions */

$this->title = "IP: " . $model->ip;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ip Restrictions'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("ip-restrictions/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title"><?= Html::encode($this->title) ?></div>
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

<div class="card bg-white">

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

    <div class="card-block">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'ip',
            ],
        ]) ?>
    </div>

</div>
