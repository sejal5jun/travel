<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 06-05-2016
 * Time: 14:49
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Change Password: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Edit Profile', 'url' => [Yii::$app->urlManager->createAbsoluteUrl("user/edit-profile")]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title">Users</div>
    <div class="sub-title"><?= Html::encode($this->title) ?></div>
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
<div class="card bg-white">
    <div class="card-header bg-danger-dark">
        <strong class="text-white">User</strong>
    </div>
    <div class="card-block">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'old_password')->passwordInput() ?>
        </div>
        <div class="col-md-3 col-md-offset-1">
            <?= $form->field($model, 'new_password')->passwordInput() ?>
        </div>
        <div class="col-md-3 col-md-offset-1">
            <?= $form->field($model, 'confirm_password')->passwordInput() ?>
        </div>

        <div class="form-group row">
            <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary btn-round pull-right']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
