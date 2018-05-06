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
$this->beginPage() ?>
<!DOCTYPE html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>
	<body class="page-loading">
		<?php $this->beginBody() ?>
			<div class="pageload">
				<div class="pageload-inner">
					<div class="sk-rotating-plane"></div>
				</div>
			</div>
			<div class="app signin usersession">
				<div class="session-wrapper">
					<div class="page-height-o row-equal align-middle">
						<div class="column">
							<div class="card bg-white no-border">
									<?= $content;?>
							</div>
						</div>
					</div>
				</div>
				<footer class="session-footer">
					<nav class="footer-right">
						<ul class="nav">
							<li>
								<a href="javascript:;">Feedback</a>
							</li>
							<li>
								<a href="javascript:;" class="scroll-up">
								<i class="fa fa-angle-up"></i>
							</a>
							</li>
						</ul>
					</nav>
					<nav class="footer-left hidden-xs">
						<ul class="nav">
							<li>
								<a href="javascript:;">Privacy</a>
							</li>
							<li>
								<a href="javascript:;">Terms</a>
							</li>
							<li>
								<a href="javascript:;">Help</a>
							</li>
						</ul>
					</nav>
				</footer>
			</div>
        <?php $this->registerJsFile('@web/js/app.min.js'); ?>
		<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>