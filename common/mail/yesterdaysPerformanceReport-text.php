<?php
/**
 * User: Pranit
 * Date: 16-11-2016
 * Time: 12:55
 */
use yii\helpers\Html;
use backend\models\enums\UserTypes;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $performanceReports[] common\models\ActivityCount */
/* @var $link String */
/* @var $total int */
?>
<div class='inquiry-report'>
	<p>Dear <?= Html::encode($user->username) ?>,</p>

	<p>Performance Report for <?= date('d M Y', $yest_date) ?></a></p>

	<!-- Labels -->
	<div style="padding: 0.25rem;">
		<table style="margin-bottom: 0!important; background-color: #fff; width: 100%; max-width: 100%; border-spacing: 0; border-collapse: collapse; font-family: Arial,'Helvetica Neue',Helvetica,sans-serif,sans-serif;">
			
			<thead>
				<tr>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">User</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Role</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Performance</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($performanceReports as $performanceReport) { ?>
				<tr>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $performanceReport->user->username; ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= UserTypes::$headers[$performanceReport->user->role]; ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?php
							if($performanceReport->user->role==UserTypes::FOLLOW_UP_MANAGER || $performanceReport->user->role==UserTypes::FOLLOW_UP_STAFF)
                                echo 'Followup Taken: '. $performanceReport->followup_count;
                            else if($performanceReport->user->role==UserTypes::QUOTATION_STAFF || $performanceReport->user->role==UserTypes::QUOTATION_MANAGER)
                                echo 'Quotation Sent: ' . $performanceReport->quotation_count;
                            else if($performanceReport->user->role==UserTypes::ADMIN)
                                echo 'Quotation Sent: ' . $performanceReport->quotation_count . '<br/>' . 'Followup Taken: '. $performanceReport->followup_count;
						?>
					</td>
				</tr>
				<?php } ?>
			</tbody>

		</table>
    </div>
</div>