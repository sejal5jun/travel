<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = 'Create Package';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packages'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("package/index")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Packages</div>
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
    'price_model' => $price_model,
    //itinerary' => $itinerary,
    'itinerary_model' => $itinerary_model,
]) ?>

