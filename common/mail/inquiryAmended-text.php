<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 20-07-2016
 * Time: 18:56
 */

/* @var $this yii\web\View */
/* @var $user common\models\User */

use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;
use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl(['inquiry/view', 'id' => $model->id]);
?>
<div class="inquiry-add">
    <p>Hello <?= Html::encode($username) ?>,</p>

    <p>Inquiry  <?= 'KR- ' . Html::encode($model->inquiry_id) ?> is amended.</p>

    <p>Please send again new quotation according to changes required.</p>

    <p>For more details <a href=" <?php echo $link?>">click here</a></p>

</div>