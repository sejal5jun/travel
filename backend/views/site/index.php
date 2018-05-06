<?php
use backend\models\enums\CustomerTypes;
use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use common\models\InquirySearch;
use common\models\Inquiry;
use yii\web\View;

$this->title = 'Travel Agency';
?>

<div class="m-x-n-g m-t-n-g overflow-hidden">
	<div class="card m-b-0 bg-primary-dark text-white p-a-md no-border">
        <?php
        $date = date("d-m-y");
         $birthday_date = "24-01-17";
         ?>
        <?php if($date == $birthday_date){?>
        <h1> Happy Birthday...</h1>
    <?php }else {?>
            <h1>Welcome To Travel Agency</h1>
    <?php }?>
	</div>
	<div class="card bg-white no-border">
	</div>
</div>

<div class="small-panel row">
	<div class="card bg-white">
		<div class="card-header small-panel-card bg-success-darker text-white">
			Customer Section
		</div>
		<div class="card-block">
			<div class="row mb25">
            <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
				<div class="col-md-4">
					<div class="card card-dashboard">
						<div class="card-header small-panel-card bg-success-darker text-white">
							PENDING INQUIRIES
						</div>
						<div class="card-block">
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Total Pending Inquiries(<?= count($all_pending_inquiries_customer)?>)</u>
							</a><br />
							<?php $priority=''?>
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Hot New Customer (<?= count($hot_new_inquiries_customer)?>)</u>
							</a><br />
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Hot Old Customer (<?= count($hot_old_inquiries_customer)?>)</u>
							</a><br />
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
                                <u> General New Customer (<?= count($general_new_inquiries_customer)?>)</u>
                            </a><br />
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
                                <u> General Old Customer (<?= count($general_old_inquiries_customer)?>)</u>
                            </a>
						</div>
					</div>
				</div>
            <?php } ?>
            <?php if(Yii::$app->user->identity->role!= UserTypes::BOOKING_STAFF){?>
				<div class="col-md-4">
					<div class="card card-dashboard">
						<div class="card-header small-panel-card bg-success-darker text-white">
							FOLLOW-UPS
						</div>
						<div class="card-block">
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Today's Followups (<?= count($todays_followups_customer)?>)</u>
							</a><br />
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Pending Followups (<?= count($pending_followups_customer)?>)</u>
							</a><br />
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> All Followups (<?= count($all_followups_customer)?>)</u>
							</a><br />
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
                                <u> Highly Interested (<?= $all_followups_hi_customer_count ?>)</u>
                            </a><br />
                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
                                <u> Not Highly Interested (<?= $all_followups_nhi_customer_count ?>)</u>
                            </a>
						</div>
					</div>
				</div>
            <?php } ?>
            <?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
                <div class="col-md-4">
					<div class="card card-dashboard">
						<div class="card-header small-panel-card bg-success-darker text-white">
							CLOSED FILES
						</div>
						<div class="card-block">
							<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Booked Inquiries (<?= count($completed_inquiries_customer) ?>)</u>
							</a><br />
							<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
								<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_customer)?>)</u>
							</a>
						</div>

					</div>
				</div>
            <?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER]); ?>">
									<u> Total Bookings(<?= count($all_bookings_customer) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div>
			<div class="row md25">
				<div class="card bg-white">
					<div class="card-header small-panel-card bg-success-darker text-white">
						Maldives section
					</div>
					<div class="card-block">
						<div class="row md25">
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								PENDING INQUIRIES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Total Pending Inquiries(<?= count($all_pending_inquiries_customer_maldives)?>)</u>
								</a><br />
								<?php $priority=''?>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Hot New Customer (<?= count($hot_new_inquiries_customer_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Hot Old Customer (<?= count($hot_old_inquiries_customer_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> General New Customer (<?= count($general_new_inquiries_customer_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> General Old Customer (<?= count($general_old_inquiries_customer_maldives)?>)</u>
								</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								FOLLOW-UPS
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Today's Followups (<?= count($todays_followups_customer_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Pending Followups (<?= count($pending_followups_customer_maldives)?>)</u>
								</a><br/>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> All Followups (<?= count($all_followups_customer_maldives)?>)</u>
								</a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
	                                <u> Highly Interested (<?= $all_followups_hi_customer_maldives_count ?>)</u>
	                            </a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
	                                <u> Not Highly Interested (<?= $all_followups_nhi_customer_maldives_count ?>)</u>
	                            </a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role !=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								CLOSED FILES
							</div>
							<div class="card-block">
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Booked Inquiries (<?= count($completed_inquiries_customer_maldives) ?>)</u>
								</a><br />
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']) ; ?>">
									<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_customer_maldives)?>)</u>
								</a>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'maldives']); ?>">
									<u> Total Bookings(<?= count($all_bookings_agent_maldives) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div>
			</div>
			</div>
			</div>
			<div class="row mb25">
				<div class="card bg-white">
					<div class="card-header small-panel-card bg-success-darker text-white">
						Mauritius section
					</div>
					<div class="card-block">
						<div class="row md25">
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								PENDING INQUIRIES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Total Pending Inquiries(<?= count($all_pending_inquiries_customer_mauritius)?>)</u>
								</a><br />
								<?php $priority=''?>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Hot New Customer (<?= count($hot_new_inquiries_customer_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Hot Old Customer (<?= count($hot_old_inquiries_customer_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> General New Customer (<?= count($general_new_inquiries_customer_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> General Old Customer (<?= count($general_old_inquiries_customer_mauritius)?>)</u>
								</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								FOLLOW-UPS
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Today's Followups (<?= count($todays_followups_customer_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Pending Followups (<?= count($pending_followups_customer_mauritius)?>)</u>
								</a><br/>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> All Followups (<?= count($all_followups_customer_mauritius)?>)</u>
								</a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
	                                <u> Highly Interested (<?= $all_followups_hi_customer_mauritius_count ?>)</u>
	                            </a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
	                                <u> Not Highly Interested (<?= $all_followups_nhi_customer_mauritius_count ?>)</u>
	                            </a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role !=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								CLOSED FILES
							</div>
							<div class="card-block">
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']) ; ?>">
									<u> Booked Inquiries (<?= count($completed_inquiries_customer_mauritius) ?>)</u>
								</a><br />
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']) ; ?>">
									<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_customer_mauritius)?>)</u>
								</a>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-success-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::CUSTOMER,'country'=>'mauritius']); ?>">
									<u> Total Bookings(<?= count($all_bookings_agent_mauritius) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div>
			</div>
			</div>
			</div>

		</div>
	</div>
	<div class="card bg-white">
		<div class="card-header small-panel-card bg-danger-darker text-white">
			Agent Section
		</div>
		<div class="card-block">
			<div class="row mb25">
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								PENDING INQUIRIES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Total Pending Inquiries(<?= count($all_pending_inquiries_agent)?>)</u>
								</a><br />
								<?php $priority=''?>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Hot New Customer (<?= count($hot_new_inquiries_agent)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Hot Old Customer (<?= count($hot_old_inquiries_agent)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> General New Customer (<?= count($general_new_inquiries_agent)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> General Old Customer (<?= count($general_old_inquiries_agent)?>)</u>
								</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role!=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								FOLLOW-UPS
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Today's Followups (<?= count($todays_followups_agent)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Pending Followups (<?= count($pending_followups_agent)?>)</u>
								</a><br/>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> All Followups (<?= count($all_followups_agent)?>)</u>
								</a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT]); ?>">
	                                <u> Highly Interested (<?= $all_followups_hi_agent_count ?>)</u>
	                            </a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT]); ?>">
	                                <u> Not Highly Interested (<?= $all_followups_nhi_agent_count ?>)</u>
	                            </a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role !=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								CLOSED FILES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT]) ; ?>">
									<u> Booked Inquiries (<?= count($completed_inquiries_agent) ?>)</u>
								</a><br />
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_agent)?>)</u>
								</a>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT]); ?>">
									<u> Total Bookings(<?= count($all_bookings_agent) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div>
			<div class="row mb25">
				<div class="card bg-white">
				<div class="card-header small-panel-card bg-danger-darker text-white">
					Maldives section
				</div>
				<div class="card-block">
					<div class="row md25">
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								PENDING INQUIRIES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Total Pending Inquiries(<?= count($all_pending_inquiries_agent_maldives)?>)</u>
								</a><br />
								<?php $priority=''?>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Hot New Customer (<?= count($hot_new_inquiries_agent_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Hot Old Customer (<?= count($hot_old_inquiries_agent_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> General New Customer (<?= count($general_new_inquiries_agent_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> General Old Customer (<?= count($general_old_inquiries_agent_maldives)?>)</u>
								</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								FOLLOW-UPS
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Today's Followups (<?= count($todays_followups_agent_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Pending Followups (<?= count($pending_followups_agent_maldives)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> All Followups (<?= count($all_followups_agent_maldives)?>)</u>
								</a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
	                                <u> Highly Interested (<?= $all_followups_hi_agent_maldives_count ?>)</u>
	                            </a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
	                                <u> Not Highly Interested (<?= $all_followups_nhi_agent_maldives_count ?>)</u>
	                            </a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role !=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								CLOSED FILES
							</div>
							<div class="card-block">
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']) ; ?>">
									<u> Booked Inquiries (<?= count($completed_inquiries_agent_maldives) ?>)</u>
								</a><br />
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']) ; ?>">
									<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_agent_maldives)?>)</u>
								</a>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'maldives']); ?>">
									<u> Total Bookings(<?= count($all_bookings_agent_maldives) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div></div>
			</div>
			</div>
			<div class="row mb25">
				<div class="card bg-white">
					<div class="card-header small-panel-card bg-danger-darker text-white">
						Mauritius section
					</div>
					<div class="card-block">
						<div class="row md25">
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								PENDING INQUIRIES
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Total Pending Inquiries(<?= count($all_pending_inquiries_agent_mauritius)?>)</u>
								</a><br />
								<?php $priority=''?>
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Hot New Customer (<?= count($hot_new_inquiries_agent_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::HOT_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Hot Old Customer (<?= count($hot_old_inquiries_agent_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_NEW_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> General New Customer (<?= count($general_new_inquiries_agent_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION, 'priority' =>InquiryPriorityTypes::GENERAL_OLD_CUSTOMER,"",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> General Old Customer (<?= count($general_old_inquiries_agent_mauritius)?>)</u>
								</a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								FOLLOW-UPS
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::TODAYS_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Today's Followups (<?= count($todays_followups_agent_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::PENDING_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Pending Followups (<?= count($pending_followups_agent_mauritius)?>)</u>
								</a><br />
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::QUOTED,"priority" => '','f_type' => InquirySearch::ALL_FOLLOWUPS,'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> All Followups (<?= count($all_followups_agent_mauritius)?>)</u>
								</a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
	                                <u> Highly Interested (<?= $all_followups_hi_agent_mauritius_count ?>)</u>
	                            </a><br />
	                            <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED,'f_type' => InquirySearch::ALL_FOLLOWUPS, 'InquirySearch[highly_interested]' => Inquiry::NOT_HIGHLY_INTERESTED, 'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
	                                <u> Not Highly Interested (<?= $all_followups_nhi_agent_mauritius_count ?>)</u>
	                            </a>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role !=UserTypes::BOOKING_STAFF){?>
					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								CLOSED FILES
							</div>
							<div class="card-block">
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Booked Inquiries (<?= count($completed_inquiries_agent_mauritius) ?>)</u>
								</a><br />
								<a href="<?=  Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index" ,'type' =>InquiryStatusTypes::CANCELLED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']) ; ?>">
									<u> Cancelled Inquiries  (<?= count($cancelled_inquiries_agent_mauritius)?>)</u>
								</a>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if(Yii::$app->user->identity->role==UserTypes::BOOKING_STAFF){?>

					<div class="col-md-4">
						<div class="card card-dashboard">
							<div class="card-header small-panel-card bg-danger-darker text-white">
								Total Bookings
							</div>
							<div class="card-block">
								<a href="<?= Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' =>InquiryStatusTypes::COMPLETED,"","",'customer_type'=>CustomerTypes::AGENT,'country'=>'mauritius']); ?>">
									<u> Total Bookings(<?= count($all_bookings_agent_mauritius) ?>)</u>
								</a>
							</div>
						</div>
					</div>



				<?php }?>
			</div>
			</div>
			</div>
			</div>
		</div>
	</div>
</div>

<!--<div class="row">
 <?php //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::QUOTATION_MANAGER || Yii::$app->user->identity->role==UserTypes::QUOTATION_STAFF){?>
        <div class="col-md-6">
		<div class="card bg-white no-border">
			<div class="p-a bb">
				RECENT INQUIRIES
			</div>
			<ul class="notifications">
				<li>
					<ul class="notifications-list">
						<?php //if (count($pending_inquiries) > 0) { ?>
							<?php //foreach ($pending_inquiries as $inq) { ?>
								<li>
									<a href="<?php // Yii::$app->urlManager->createAbsoluteUrl(["inquiry/view", 'id' => $inq->id]); ?>">
										<div class="notification-icon">
											<div class="circle-icon bg-danger text-white">
												<i class="icon-question"></i>
											</div>
										</div>
                                        <span class="notification-message">
                                            <b><?php // strtoupper($inq->name); ?></b>
                                        </span>
                                        <span class="notification-message">
                                            <b><?php // strtoupper($inq->destination); ?></b>
                                        </span>
                                        <span class="notification-message">
                                            <b><?php // strtoupper(date('M-d-Y', $inq->from_date)); ?></b>
                                        </span>
									</a>
								</li>
							<?php //} ?>
						<?php //} else { ?>
							<li>
								<a href="#">
									<div class="notification-icon">
										<div class="circle-icon bg-danger text-white">
											<i class="icon-question"></i>
										</div>
									</div>
                                    <span class="notification-message">
										<b>NO PENDING INQUIRIES !!!</b>
									</span>
								</a>
							</li>
						<?php// } ?>
					</ul>
				</li>
			</ul>
			<?php //if ($pending_inquiries_count > 5) { ?>
				<div class="p-a">
					<a href="<?php // Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::IN_QUOTATION]); ?>"
					   class="btn btn-primary btn-sm ">View All</a>
				</div>
			<?php //} ?>
		</div>

	</div>
 <?php //} ?>

 <?php //if(Yii::$app->user->identity->role==UserTypes::ADMIN || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_MANAGER || Yii::$app->user->identity->role==UserTypes::FOLLOW_UP_STAFF){?>
    <div class="col-md-6">
		<div class="card bg-white no-border">
			<div class="p-a bb">
				TODAY'S FOLLOWUPS
			</div>
			<ul class="notifications">
				<li>
					<ul class="notifications-list">

						<?php //if (count($todays_followups_count) > 0) { ?>
							<?php //foreach ($todays_followups as $fw) { ?>
								<li>
									<a href="<?php // Yii::$app->urlManager->createAbsoluteUrl(["inquiry/quoted-inquiry", 'id' => $fw->inquiry_id]); ?>">

										<div class="notification-icon">
											<div class="circle-icon bg-danger text-white">
												<i class="icon-call-out"></i>
											</div>
                                        </div>
										<span class="notification-message">
											<b><?php // strtoupper($fw->inquiry->name); ?></b>
										</span>
										<span class="notification-message">
											<b><?php // strtoupper($fw->inquiry->destination); ?></b>
										</span>
										<span class="notification-message">
											<b><?php // strtoupper(date('M-d-Y', $fw->inquiry->from_date)); ?></b>
										</span>

									</a>
								</li>
							<?php // } ?>
						<?php //} else { ?>
							<li>
								<a href="#">
									<div class="notification-icon">
										<div class="circle-icon bg-danger text-white">
											<i class="icon-pin"></i>
										</div>
									</div>
                                    <span class="notification-message">
										<b>NO FOLLOWUPS TODAY !!!</b>
									</span>
								</a>
							</li>
						<?php //} ?>
					</ul>
				</li>
			</ul>
			<?php// if ($todays_followups_count > 5) { ?>
				<div class="p-a">
					<a href="<?php // Yii::$app->urlManager->createAbsoluteUrl(["inquiry/index", 'type' => InquiryStatusTypes::QUOTED, 'InquirySearch[followup_type]' => 2]); ?>"
					   class="btn btn-primary btn-sm ">View All</a>
				</div>
			<?php// } ?>
		</div>

	</div>
 <?php //} ?>


		</div>-->
<?php $this->registerJs('
 $("document").ready(function(){
    $(".notification-alert").on("click", function () {
        $(this).fadeOut(1000);
    });
 });
');