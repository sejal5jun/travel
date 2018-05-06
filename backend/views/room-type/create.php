<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RoomType */

$this->title = 'Add Room Type';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Room Types'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("room-type/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Agents</div>
    <div class="sub-title"><?= Html::encode($this->title) ?></div>
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
