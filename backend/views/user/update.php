<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Update User: ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl("user/index")]];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["user/view",'id' => $model->id])]];
$this->params['breadcrumbs'][] = 'Update';
?>
    <div class="page-title">
        <div class="title">Users</div>
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
