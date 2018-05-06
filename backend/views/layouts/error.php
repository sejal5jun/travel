<?php 

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);

?>
<!doctype html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>
	<body class="page-loaded">
		<?php $this->beginBody() ?>
			<!--<div class="pageload">
				<div class="pageload-inner">
					<div class="sk-rotating-plane"></div>
				</div>
			</div>-->
			<div class="app error-page usersession">
				<div class="session-wrapper">
					<div class="page-height-o row-equal align-middle text-center">
						<?= $content;?>
					</div>
				</div>
			</div>
		<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>