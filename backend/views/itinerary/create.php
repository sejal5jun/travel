<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Itinerary */

$this->title = 'Create Itinerary';
$this->params['breadcrumbs'][] = ['label' => 'Itineraries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="itinerary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
