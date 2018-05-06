<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\IpRestrictions */

$this->title = 'Add IP Restrictions';
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
<?= $this->render('_form', [
    'model' => $model,
]) ?>