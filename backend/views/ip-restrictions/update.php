<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\IpRestrictions */

$this->title = 'Update Ip Restrictions: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ip Restrictions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
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

<?= $this->render('_form', [
    'model' => $model,
]) ?>