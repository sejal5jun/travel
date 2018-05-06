<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="page-height row-equal align-middle text-center">
	<div class="column">
		<div class="error-number">
			<span><?= $exception->statusCode;?></span>
		</div>
		<div class="m-b h4"><?= $name;?></div>
		<p><?= nl2br(Html::encode($message)) ?></p>
		<a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index");?>" class="btn btn-primary m-r">Return home</a>
	</div>
</div>
