<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InquiryPackage */

$this->title = 'Create Inquiry Package';
$this->params['breadcrumbs'][] = ['label' => 'Inquiry Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-package-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
