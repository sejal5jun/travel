<?php
/**
 * User: Pranit
 * Date: 15-11-2016
 * Time: 14:10
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $inquiryReport common\models\InquiryReport */
/* @var $link String */
/* @var $total int */
?>
<div class='inquiry-report'>
	<p>Dear <?= Html::encode($user->username) ?>,</p>

	<p>Inquiry Report for <?= date('d M Y', $yest_date) ?></a></p>

	<!-- Labels -->
	<div style="padding: 0.25rem;">
		<table style="margin-bottom: 0!important; background-color: #fff; width: 100%; max-width: 100%; border-spacing: 0; border-collapse: collapse; font-family: Arial,'Helvetica Neue',Helvetica,sans-serif,sans-serif;">
			
			<thead>
				<tr>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Inquiry Added</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Quotation Sent</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Followups Taken</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Bookings</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Cancelled Inquiries</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($inquiryReports as $inquiryReport) { ?>
				<tr>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $inquiryReport->new_inquiry_count ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $inquiryReport->quotation_count ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $inquiryReport->followup_count ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $inquiryReport->booking_count ?>
					</td>
					<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
						<?= $inquiryReport->cancellation_count ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>

		</table>
    </div>
</div>