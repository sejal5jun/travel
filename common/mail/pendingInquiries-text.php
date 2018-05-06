<?php
/**
 * User: Pranit
 * Date: 15-11-2016
 * Time: 14:10
 */
use yii\helpers\Html;
use backend\models\enums\InquiryTypes;
use backend\models\enums\SourceTypes;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $inquiries[] common\models\Inquiry */
/* @var $link String */
/* @var $total int */
?>
<div class='inquiry-reminder'>
	<p>Dear <?= Html::encode($user->username) ?>,</p>

	<p>You have total <strong><?= $total ?></strong> pending inquiries.</p>

	<p>For more details <a href='<?= $link ?>'>click here</a></p>

	<!-- Labels -->
	<div style="padding: 0.25rem;">
		<label style="font-weight: 500; display: inline-block; max-width: 100%; margin-bottom: 5px;">
			<span style="display: inline-block; height: 10px; width: 5px; background-color: #DD6777;"></span>&nbsp;&nbsp;<span style="font-size: small">Hot New Customer</span>
		</label>&nbsp;
		<label style="font-weight: 500; display: inline-block; max-width: 100%; margin-bottom: 5px;">
			<span style="display: inline-block; height: 10px; width: 5px; background-color: #6164C1;"></span>&nbsp;&nbsp;<span style="font-size: small">Hot Old Customer</span>
		</label>&nbsp;
		<label style="font-weight: 500; display: inline-block; max-width: 100%; margin-bottom: 5px;">
			<span style="display: inline-block; height: 10px; width: 5px; background-color: #6EC7E6;"></span>&nbsp;&nbsp;<span style="font-size: small">General New Customer</span>
		</label>&nbsp;
		<label style="font-weight: 500; display: inline-block; max-width: 100%; margin-bottom: 5px;">
			<span style="display: inline-block; height: 10px; width: 5px; background-color: #F2B776;"></span>&nbsp;&nbsp;<span style="font-size: small">General Old Customer</span>
		</label>
		<br /><br />

		<table style="margin-bottom: 0!important; background-color: #fff; width: 100%; max-width: 100%; border-spacing: 0; border-collapse: collapse; font-family: Arial,'Helvetica Neue',Helvetica,sans-serif,sans-serif;">
			
			<thead>
				<tr>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;"></th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Inquiry Id</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Passenger Name</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Destination</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Date of Travel</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Passenger Mobile</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Staff</th>
					<th style="padding: .35rem 0.5rem !important; border-top: 0px;border-color: #e4e4e4; font-weight: 400; text-transform: uppercase; border-bottom-width: 0; vertical-align: bottom; border-bottom: 2px solid #ddd; line-height: 1.42857143;     white-space: nowrap; text-align: left;">Added On</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($inquiries as $inquiry) { ?>
					<tr>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<div style="height: 15px; width: 5px; <?php 
								if($inquiry->inquiry_priority == 1) {
									echo 'background-color: #DD6777;';
								} elseif ($inquiry->inquiry_priority == 2) {
									echo 'background-color: #6164C1;';
								} elseif ($inquiry->inquiry_priority == 3) {
									echo 'background-color: #6EC7E6;';
								} elseif ($inquiry->inquiry_priority == 4) {
									echo 'background-color: #F2B776;';
								}
								?>"></div>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?= "TA-" . $inquiry->inquiry_id; ?>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<span style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; ">
								<?= $inquiry->name; ?>
							</span>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?= $inquiry->destination; ?>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?= $inquiry->date_with_days; ?><!--Oct-29-2016 (3 Nights)-->
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?= $inquiry->mobile; ?>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?php if($inquiry->quotationManager != '')
                                echo $inquiry->quotationManager->username;
                            else
                                echo '(not set)';
                            ?>
						</td>
						<td style="padding: .35rem 0.5rem !important; border-color: #e4e4e4; line-height: 1.42857143; vertical-align: top; border-top: 1px solid #ddd;">
							<?= date('d M y h:m a', $inquiry->created_at); ?><!--27 October 2016 11:39 AM-->
						</td>
					</tr>
				<?php } ?>
			</tbody>

		</table>
    </div>
</div>