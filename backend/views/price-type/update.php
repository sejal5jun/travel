<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PriceType */

$this->title = 'Update Price Type: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Price Type'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("price-type/index")]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="page-title">
    <div class="title">Price Type</div>
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
