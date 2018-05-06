<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Inquiry */
/* @var $age_model common\models\InquiryChildAge */
/* @var $agent_model common\models\Agent */

$this->title = 'Update Inquiry: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inquiries'), 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => \backend\models\enums\InquiryStatusTypes::IN_QUOTATION])]];
$this->params['breadcrumbs'][] = ['label' => 'KR-' . $model->inquiry_id, 'url' => [Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view",'id' => $model->id])]];
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
    'age_model'=>$age_model,
    'agent_model'=>$agent_model,
    'city'=>$city,
    'room_arr' => $room_arr,
    'child_age' => $child_age,
    'city_name' => $city_name,
]) ?>
