<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Followup */

$this->title = 'Create Followup';
$this->params['breadcrumbs'][] = ['label' => 'Followups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="followup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
