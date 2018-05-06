<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PackagePricing */

$this->title = 'Create Package Pricing';
$this->params['breadcrumbs'][] = ['label' => 'Package Pricings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-pricing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
