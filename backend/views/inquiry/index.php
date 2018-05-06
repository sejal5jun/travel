<?php


use backend\models\enums\InquiryPriorityTypes;
use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\InquiryTypes;
use backend\models\enums\UserTypes;
use common\models\Followup;
use common\models\Inquiry;
use common\models\InquirySearch;
use common\models\User;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel_pending common\models\InquirySearch */
/* @var $searchModel_quoted common\models\InquirySearch */
/* @var $searchModel_in_follow_up common\models\InquirySearch */
/* @var $searchModel_completed common\models\InquirySearch */
/* @var $searchModel_cancelled common\models\InquirySearch */
/* @var $dataProvider_pending yii\data\ActiveDataProvider */
/* @var $dataProvider_quoted yii\data\ActiveDataProvider */
/* @var $dataProvider_in_follow_up yii\data\ActiveDataProvider */
/* @var $dataProvider_completed yii\data\ActiveDataProvider */
/* @var $dataProvider_cancelled yii\data\ActiveDataProvider */

$heading = '';
if($type=='' || $type==InquiryStatusTypes::IN_QUOTATION || $type==InquiryStatusTypes::CANCELLED)
    $heading='Inquiries';
if($type==InquiryStatusTypes::QUOTED)
    $heading='Followups';
if($type==InquiryStatusTypes::COMPLETED)
    $heading='Bookings';
$this->title = $heading;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-title">
    <div class="title"><?=$heading?></div>
</div>
<ol class="breadcrumb">
    <li>
        <a href="<?= Yii::$app->urlManager->createAbsoluteUrl("site/index"); ?>">Dashboard</a>
    </li>
    <?php foreach ($this->params['breadcrumbs'] as $k => $v) {
        if (isset($v['label'])) {
            echo "<li><a href=" . $v['url'][0] . ">" . $v['label'] . "</a></li>";
        } else {
            echo "<li class='active ng-binding'>$v</li>";
        }
    }?>
</ol>
<?php

?>
<?php if($type==InquiryStatusTypes::QUOTED){ ?>
    <?php echo $this->render('_search', [
        'model' => $searchModel_quoted,
        'followup_type' => $followup_type,
        'f_type' => $f_type,
        'type' => $type,
        'priority' => $priority,
    ])?>
<?php } ?>
<?php if($type==InquiryStatusTypes::IN_QUOTATION){ ?>
    <?php echo $this->render('_inquiry_search', [
        'model' => $searchModel_pending,
        'type' => $type,
        'priority' => $priority,

    ])?>
<?php } ?>
<?php if($type==InquiryStatusTypes::COMPLETED){ ?>
    <?php echo $this->render('_inquiry_search', [
        'model' => $searchModel_completed,
        'type' => $type,
        'priority' => $priority,

    ])?>
<?php } ?>
<?php if($type==InquiryStatusTypes::CANCELLED){ ?>
    <?php echo $this->render('_inquiry_search', [
        'model' => $searchModel_cancelled,
        'type' => $type,
        'priority' => $priority,
    ])?>
<?php } ?>
<?php if($type==""){ ?>
    <?php echo $this->render('_inquiry_search', [
        'model' => $searchModel,
        'type' => $type,
        'priority' => $priority,
    ])?>
<?php } ?>
<?php
    $followup_type = '';
  if($f_type=='' || $f_type == InquirySearch::ALL_FOLLOWUPS)
    $followup_type = "All Followups";
    if($f_type == InquirySearch::PENDING_FOLLOWUPS)
        $followup_type = "Pending Followups";
    if($f_type == InquirySearch::TODAYS_FOLLOWUPS)
        $followup_type = "Today's Followups";

?>
<div class="card bg-white">
    <div class="card-header bg-danger-dark">
        <Strong class="text-white"><?= $type!='' ? $type==InquiryStatusTypes::QUOTED ? $followup_type : InquiryStatusTypes::$status[$type] :'All Inquiries'?></Strong>
        <?php if($type==InquiryStatusTypes::COMPLETED){?>
            <a href="<?=Yii::$app->getUrlManager()->createUrl(['/booking/index'])?>"><Strong class="text-white pull-right"><i class="icon-calendar"></i> Calendar View</Strong></a>
        <?php } ?>
    </div>

    <?php if($type==InquiryStatusTypes::IN_QUOTATION){ ?>

        <div class="card-block">
            <label class="control-label"><span class="inline bullet-label bg-danger"></span>&nbsp;&nbsp;<span style="font-size: small">Hot New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><span class="inline bullet-label bg-primary"></span>&nbsp;&nbsp;<span style="font-size: small">Hot Old Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><span class="inline bullet-label bg-info"></span>&nbsp;&nbsp;<span style="font-size: small">General New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><span class="inline bullet-label bg-warning"></span>&nbsp;&nbsp;<span style="font-size: small">General Old Customer</span></label>
            <button id="btn-highly-interested" class="btn btn-primary pull-right" disabled>Highly interested</button>
            <br />  <br />


            <?php
            $array = [
                ['id' => InquiryStatusTypes::IN_QUOTATION, 'name' => InquiryStatusTypes::$headers[InquiryStatusTypes::IN_QUOTATION]],
                ['id' => InquiryStatusTypes::AMENDED, 'name' => InquiryStatusTypes::$headers[InquiryStatusTypes::AMENDED]],
            ];
            $inquiry_type=[
                ['id' => InquiryTypes::PACKAGE_WITH_ITINERARY, 'name' => InquiryTypes::$headers[InquiryTypes::PACKAGE_WITH_ITINERARY]],
                ['id' => InquiryTypes::PACKAGE_WITHOUT_ITINERARY, 'name' => InquiryTypes::$headers[InquiryTypes::PACKAGE_WITHOUT_ITINERARY]],
                ['id' => InquiryTypes::PER_ROOM_PER_NIGHT, 'name' => InquiryTypes::$headers[InquiryTypes::PER_ROOM_PER_NIGHT]],
            ];
            $inquiry_priority=[
                ['id' => InquiryPriorityTypes::HOT_NEW_CUSTOMER, 'name' => InquiryPriorityTypes::$headers[InquiryPriorityTypes::HOT_NEW_CUSTOMER]],
                ['id' => InquiryPriorityTypes::HOT_OLD_CUSTOMER, 'name' => InquiryPriorityTypes::$headers[InquiryPriorityTypes::HOT_OLD_CUSTOMER]],
                ['id' => InquiryPriorityTypes::GENERAL_NEW_CUSTOMER, 'name' => InquiryPriorityTypes::$headers[InquiryPriorityTypes::GENERAL_NEW_CUSTOMER]],
                ['id' => InquiryPriorityTypes::GENERAL_OLD_CUSTOMER, 'name' => InquiryPriorityTypes::$headers[InquiryPriorityTypes::GENERAL_OLD_CUSTOMER]],

            ];
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider_pending,
                // 'filterModel' => $searchModel_pending,
                'tableOptions' => [
                    'class' => 'custom-table table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->id,
                        'class' => 'card-custom-height' . ($model->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                    /* if($model->inquiry_priority == 1){
                         return [
                             'data-id' => $model->id,
                             'class' => 'card-custom-danger',
                         ];
                        }
                     if($model->inquiry_priority == 2){
                         return [
                             'data-id' => $model->id,
                             'class' => 'card-custom-primary',
                         ];
                     }
                     if($model->inquiry_priority == 3){
                         return [
                             'data-id' => $model->id,
                             'class' => 'card-custom-info',
                         ];
                     }
                     if($model->inquiry_priority == 4){
                         return [
                             'data-id' => $model->id,
                             'class' => 'card-custom-warning',
                         ];
                     }
                     else{
                         return [
                             'data-id' => $model->id,
                             'class' => 'card-custom-height'
                         ];
                     }*/
                },
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'cssClass' => 'pending-checkboxes',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }
                    ],
                    [
                        //'attribute' => 'inquiry_priority',
                        'format'=>'raw',
                        'label'=>'',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value'=>function($data){
                            if($data->inquiry_priority == 1)
                                return '<div class="bullet bg-danger"></div>';
                            if($data->inquiry_priority == 2)
                                return '<div class="bullet bg-primary" style="color: #59595A;" class="ellipsis"></div>';
                            if($data->inquiry_priority == 3)
                                return '<div class="bullet bg-info"></div>';
                            if($data->inquiry_priority == 4)
                                return '<div class="bullet bg-warning"></div>';
                        },
                    ],
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry_id;
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value' => function($data){
                            return  '<span  class="ellipsis pending_index">'.$data->name.'</span>';
                        },
                    ],
                    [
                        'attribute' => 'destination',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'from_date',
                        'contentOptions' => ['class' => 'pending_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_pending,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],]),
                        'value' => function($data){
                            return $data->date_with_days;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'mobile',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value' => function($data){
                            return "<a href='tel:$data->mobile'>".$data->mobile."</a>";
                        },
                    ],
                    /*  [
                          'attribute' => 'inquiry_priority',
                          'format'=>'raw',
                          'contentOptions' => ['class' => 'pending_index'],
                          'value'=>function($data){
                              return '<span class="ellipsis">'.InquiryPriorityTypes::$headers[$data->inquiry_priority].'</span>';
                          },
                          'filter'=>Html::activeDropDownList($searchModel_pending, 'inquiry_priority', ArrayHelper::map($inquiry_priority, 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Select Type']),
                      ],*/

                    [
                        'attribute' => 'quotation_manager',
                        'contentOptions' => ['class' => 'pending_index'],
                        'visible' => (Yii::$app->user->identity->role != UserTypes::QUOTATION_STAFF && Yii::$app->user->identity->role != UserTypes::FOLLOW_UP_STAFF),
                        'label' => 'Staff',
                        'value' => function($data){
                            if($data->quotationManager!='')
                                return $data->quotationManager->username;
                            else
                                return '(not set)';
                        }
                    ],
                    'created_at:datetime'
                ],
            ]);
            ?>
        </div>
    <?php }?>

    <?php if($type==InquiryStatusTypes::QUOTED){ ?>
        <div class="card-block">
          <div class="row">
          <div class="col-sm-9">
            <label class="control-label"><div class="inline bullet-label bg-danger"></div>&nbsp;&nbsp;<span style="font-size: small">Hot New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-primary"></div>&nbsp;&nbsp;<span style="font-size: small">Hot Old Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-info"></div>&nbsp;&nbsp;<span style="font-size: small">General New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-warning"></div>&nbsp;&nbsp;<span style="font-size: small">General Old Customer</span></label>
          </div>
              <div class="col-sm-1">
            <button id="btn-cancel-followups" class="btn btn-primary " disabled>Cancel</button>
              </div>
              <div class="col-sm-2">
                  <button id="btn-highly-interested" class="btn btn-primary" disabled>Highly interested</button>
            </div>
            <br />  <br />

            <?php $dataProvider_quoted->key = 'inquiry_id'; ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider_quoted,
                //'filterModel' => $searchModel_quoted,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->inquiry->id,
                        'class' => 'card-custom-height quoted_index' . ($model->inquiry->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                },
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'cssClass' => 'followup-checkboxes',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->inquiry_id];
                        }
                    ],
                    [
                        //'attribute' => 'inquiry_priority',
                        'format'=>'raw',
                        'label'=>'',
                        'contentOptions' => ['class' => 'quoted_index'],
                        'value'=>function($data){
                            if($data->inquiry->inquiry_priority == 1)
                                return '<div class="bullet bg-danger"></div>';
                            if($data->inquiry->inquiry_priority == 2)
                                return '<div class="bullet bg-primary" style="color: #59595A;" class="ellipsis"></div>';
                            if($data->inquiry->inquiry_priority == 3)
                                return '<div class="bullet bg-info"></div>';
                            if($data->inquiry->inquiry_priority == 4)
                                return '<div class="bullet bg-warning"></div>';
                        },
                    ],
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry->inquiry_id;
                        },
                    ],
                    [
                        'attribute'=>'pn',
                        'label'=>'Passenger Name',
                        'value' => 'inquiry.name',
                        'contentOptions' => ['class' => 'quoted_index'],
                    ],
                    [
                        'attribute' => 'dest',
                        'label' => 'Destination',
                        'value' => 'inquiry.destination',
                        'contentOptions' => ['class' => 'quoted_index'],
                    ],
                    [
                        'attribute' => 'pess_date',

                        // 'value' => 'inquiry.from_date',
                        'contentOptions' => ['class' => 'quoted_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_quoted,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],]),
                        'value' => function($data){
                            return $data->inquiry->date_with_days;
                        },
                    ],
                    [   'format' => 'raw',
                        'attribute' => 'pess_mobile',
                        'label' => 'passenger Mobile',
                        'contentOptions' => ['class' => 'quoted_index'],
                        'value' => function($data){
                          return "<a href='tel:".$data->inquiry->mobile."'>".$data->inquiry->mobile."</a>";
                           //return $data->inquiry->mobile;

                        },

                    ],
                    [
                        'attribute' => 'fu_manager',
                        'label' => 'Staff',
                        'visible' => (Yii::$app->user->identity->role != UserTypes::QUOTATION_STAFF && Yii::$app->user->identity->role != UserTypes::FOLLOW_UP_STAFF),
                        'value' => function($data){
                            if($data->inquiry->followUpHead!='')
                                return $data->inquiry->followUpHead->username;
                            else
                                return '(not set)';
                        },
                        'contentOptions' => ['class' => 'quoted_index'],
                    ],
                    'created_at:datetime'
                ],
            ]); ?>
            <div id="loader" style="
                width: 100%;
                height: 95%;
                background-color: #cccCCC;
                position: absolute;
                opacity: 0.5;
                top: 106px;
                left: 0px;
                display: none;
            ">
              <div class="sk-rotating-plane center-block m-y-lg" style="
                margin: auto;
                margin-top: 50% !important;
            "></div>
            </div>
        </div>
    <?php }?>

    <?php if($type==InquiryStatusTypes::COMPLETED){?>
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider_completed,
                //'filterModel' => $searchModel_completed,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->id,
                        'class' => 'card-custom-height pending_index' . ($model->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry_id;
                        },
                    ],
                    [
                        'label' => 'Booking Id',
                        'value' => function($data){
                            if (isset($data->bookings[0])) {
                                return $data->bookings[0]->booking_id;
                            } else {
                                return "";
                            }
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'destination',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'from_date',
                        'contentOptions' => ['class' => 'pending_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_completed,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],
                            ]),
                        'value' => function($data){
                            return $data->date_with_days;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'mobile',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value' =>function($data) {
                            return "<a href='tel:$data->mobile'>".$data->mobile."</a>";
                        },
                    ],
                    [
                        'attribute' => 'booking_staff',
                        'contentOptions' => ['class' => 'pending_index'],
                        'visible' => (Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF ),
                        'label' => 'Staff',
                        'value' => function($model){
                            if(isset($model->bookings[0]->booking_staff)!="")
                                return $model->bookings[0]->bookingStaff->username;
                        },
                    ],
                    'created_at:datetime'
                ],
            ]); ?>
        </div>
    <?php }?>

    <?php if($type==InquiryStatusTypes::CANCELLED){?>
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider_cancelled,
                //'filterModel' => $searchModel_cancelled,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->id,
                        'class' => 'card-custom-height pending_index' . ($model->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry_id;
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'destination',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'from_date',
                        'contentOptions' => ['class' => 'pending_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_cancelled,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],]),
                            'value' => function($data){
                                return $data->date_with_days;
                            },
                    ],
                    [   'format' => 'raw',
                        'attribute' => 'mobile',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value' => function($data){
                            return "<a href='tel:$data->mobile'>".$data->mobile."</a>";
                        },
                    ],
                    [
                        'attribute' => 'head',
                        'contentOptions' => ['class' => 'pending_index'],
                        'label' => 'Inquiry Head',
                        'value' => 'inquiryHead.username'
                    ],
                    'created_at:datetime'
                ],
            ]); ?>
        </div>
    <?php }?>

    <?php if($type==InquiryStatusTypes::VOUCHERED){?>
        <div class="card-block">
            <?= GridView::widget([
                'dataProvider' => $dataProvider_vouchered,
                //'filterModel' => $searchModel_completed,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->id,
                        'class' => 'card-custom-height pending_index' . ($model->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                },
                'columns' => [
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry_id;
                        },
                    ],
                    [
                        'label' => 'Booking Id',
                        'value' => function($data){
                            if (isset($data->bookings[0])) {
                                return $data->bookings[0]->booking_id;
                            } else {
                                return "";
                            }
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'destination',
                        'contentOptions' => ['class' => 'pending_index'],
                    ],
                    [
                        'attribute' => 'from_date',
                        'contentOptions' => ['class' => 'pending_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_completed,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],
                        ]),
                        'value' => function($data){
                            return $data->date_with_days;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'attribute' => 'mobile',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value' =>function($data) {
                            return "<a href='tel:$data->mobile'>".$data->mobile."</a>";
                        },
                    ],
                    [
                        'attribute' => 'booking_staff',
                        'contentOptions' => ['class' => 'pending_index'],
                        'visible' => (Yii::$app->user->identity->role != UserTypes::BOOKING_STAFF ),
                        'label' => 'Staff',
                        'value' => function($model){
                            if(isset($model->bookings[0]->booking_staff)!="")
                                return $model->bookings[0]->bookingStaff->username;
                        },
                    ],
                    'created_at:datetime'
                ],
            ]); ?>
        </div>
    <?php }?>

    <?php if($type==''){ $array = [
        ['id' => InquiryStatusTypes::IN_QUOTATION, 'name' => InquiryStatusTypes::$index_status[InquiryStatusTypes::IN_QUOTATION]],
        ['id' => InquiryStatusTypes::QUOTED, 'name' => InquiryStatusTypes::$index_status[InquiryStatusTypes::QUOTED]],
        ['id' => InquiryStatusTypes::AMENDED, 'name' => InquiryStatusTypes::$index_status[InquiryStatusTypes::AMENDED]],
        ['id' => InquiryStatusTypes::COMPLETED, 'name' => InquiryStatusTypes::$index_status[InquiryStatusTypes::COMPLETED]],
        ['id' => InquiryStatusTypes::CANCELLED, 'name' => InquiryStatusTypes::$index_status[InquiryStatusTypes::CANCELLED]],
    ];?>
        <div class="card-block">
            <label class="control-label"><div class="inline bullet-label bg-danger"></div>&nbsp;&nbsp;<span style="font-size: small">Hot New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-primary"></div>&nbsp;&nbsp;<span style="font-size: small">Hot Old Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-info"></div>&nbsp;&nbsp;<span style="font-size: small">General New Customer</span></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label class="control-label"><div class="inline bullet-label bg-warning"></div>&nbsp;&nbsp;<span style="font-size: small">General Old Customer</span></label>
            <br />  <br />

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-hover table-condensed responsive m-b-0',
                ],
                'rowOptions'   => function ($model, $key, $index, $grid) {
                    return [
                        'data-id' => $model->id,
                        'class' => 'card-custom-height all_index' . ($model->highly_interested == Inquiry::HIGHLY_INTERESTED ? ' highly-interested' : '')
                    ];
                },
                'columns' => [
                    [
                        //'attribute' => 'inquiry_priority',
                        'format'=>'raw',
                        'label'=>'',
                        'contentOptions' => ['class' => 'pending_index'],
                        'value'=>function($data){
                            if($data->inquiry_priority == 1)
                                return '<div class="bullet bg-danger"></div>';
                            if($data->inquiry_priority == 2)
                                return '<div class="bullet bg-primary" style="color: #59595A;" class="ellipsis"></div>';
                            if($data->inquiry_priority == 3)
                                return '<div class="bullet bg-info"></div>';
                            if($data->inquiry_priority == 4)
                                return '<div class="bullet bg-warning"></div>';
                        },
                    ],
                    [
                        'attribute' => 'inquiry_id',
                        'value'=>function($data){
                            return "KR-" . $data->inquiry_id;
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'contentOptions' => ['class' => 'all_index'],
                    ],
                    [
                        'attribute' => 'destination',
                        'contentOptions' => ['class' => 'all_index'],
                    ],
                    [
                        'attribute' => 'from_date',
                        'contentOptions' => ['class' => 'all_index'],
                        'label' => 'Date Of Travel',
                        'format'=>'html',
                        'filter' => DatePicker::widget(['language' => 'ru',
                            'attribute'=>'from_date' ,
                            'model'=>$searchModel_cancelled,
                            'dateFormat' => 'M-dd-yyyy',
                            'options'=>['class'=>'form-control'],]),
                        'value' => function($data){
                            return $data->date_with_days;
                        },
                    ],
                    [   'format' => 'raw',
                        'attribute' => 'mobile',
                        'contentOptions' => ['class' => 'all_index'],
                        'value' => function($data){
                            return "<a href='tel:$data->mobile'>".$data->mobile."</a>";
                        },
                    ],
                    [
                        'contentOptions' => ['class' => 'all_index'],
                        'label' => 'Staff',
                        'visible' => (Yii::$app->user->identity->role != UserTypes::QUOTATION_STAFF && Yii::$app->user->identity->role != UserTypes::FOLLOW_UP_STAFF),
                        'value' => function ($data) {
                            if ($data->status == InquiryStatusTypes::IN_QUOTATION || $data->status == InquiryStatusTypes::AMENDED) {
                              return isset($data->quotationStaff->username) ? $data->quotationStaff->username : $data->quotationManager->username;
                            }
                            if ($data->status == InquiryStatusTypes::QUOTED) {
                                return isset($data->followUpStaff->username) ? $data->followUpStaff->username : $data->followUpHead->username;
                            }
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'contentOptions' => ['class' => 'all_index'],
                        'format' => 'raw',
                        'value' => function($data){
                            if($data->status==InquiryStatusTypes::IN_QUOTATION)
                                return '<span class="badge bg-warning-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                            if($data->status==InquiryStatusTypes::AMENDED)
                                return '<span class="badge bg-danger-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                            if($data->status==InquiryStatusTypes::QUOTED)
                                return '<span class="badge bg-info-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                            if($data->status==InquiryStatusTypes::COMPLETED)
                                return '<span class="badge bg-success-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                            if($data->status==InquiryStatusTypes::CANCELLED)
                                return '<span class="badge bg-danger-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                            if($data->status==InquiryStatusTypes::VOUCHERED)
                                return '<span class="badge bg-dark">'.InquiryStatusTypes::$index_status[$data->status].'<span>';
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map($array, 'id', 'name'), ['class' => 'form-control', 'prompt' => 'Select Status']),
                    ],
                    'created_at:datetime'
                ],
            ]); ?>
        </div>
    <?php }?>

</div>
<div class="modal bs-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change Status Of Inquiry</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group form-material">

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="control-label">Status</label>
                            <?= $form->field($model, 'status')->dropDownList(InquiryStatusTypes::$titles,['id' => 'status', 'prompt' => 'Select Status'])->label(false); ?>
                        </div>
                        <div id="followupdate" class="col-sm-6" style="display: none;">
                            <?= $form->field($followup_model, 'date')->textInput([ 'class' => 'followupdate form-control', 'name' => 'Followup[date]', 'data-provide' => 'datepicker', 'data-date-format' => 'M-dd-yyyy', 'data-date-start-date' => '0d']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="control-label">Notes</label>
                            <?= $form->field($model, 'notes')->textarea(['rows' => 6,'class'=>'summernote'])->label(false) ?>
                        </div>
                    </div>
                    <?= Html::hiddenInput('inquiry_id', null, ['id' => 'inquiry_id']); ?>

                </div>
                <div class="modal-footer no-border">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right', 'id' => 'update_status']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $(document).ready(function(){
		$('.pending_index').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this)
                location.href = '" . Url::to(['inquiry/view']) . "&id=' + id;
        });
    	$('.quoted_index').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this)
                location.href = '" . Url::to(['inquiry/quoted-inquiry']) . "&id=' + id;
        });
        $('.all_index').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this)
                location.href = '" . Url::to(['inquiry/timeline']) . "&id=' + id;
        });

        $('#status').change(function(){
            var status=$('#status').children('option').filter(':selected').text();

            if(status=='Next Follow Up')
            {
                $('#followupdate').show();
            } else {
                $('#followupdate').hide();
            }
        });

        $('.md-edit').on('click', function () {
            var id = $(this).data('id');
            $('#inquiry_id').val(id);
        });

      $('.pending-checkboxes, .select-on-check-all').on('change', function() {
            var disableButton = true;
            $.each($('.pending-checkboxes'), function(index, value) {
                if($(value).is(':checked')) {
                    disableButton = false;
                    return false;
                }
            });
            $('#btn-highly-interested').prop('disabled', disableButton);
        });

          $('#btn-highly-interested').on('click', function() {
            var keys = $('#w1').yiiGridView('getSelectedRows');
            $('#loader').show();
            $.ajax({
                method: 'POST',
                url: '" . Yii::$app->urlManager->createUrl("inquiry/highly-interested") . "',
                data: {
                    ids: keys
                },
                success: function(data) {
                    $('#loader').hide();
                    console.log(data);
                    location.reload();
                },
                error: function(data) {
                    $('#loader').hide();
                    console.log('error', data);
                }
            });
        });

        $('.followup-checkboxes, .select-on-check-all').on('change', function() {
            var disableButton = true;
            $.each($('.followup-checkboxes'), function(index, value) {
                if($(value).is(':checked')) {
                    disableButton = false;
                    return false;
                }
            });
            $('#btn-cancel-followups').prop('disabled', disableButton);
            $('#btn-highly-interested').prop('disabled', disableButton);
        });

        $('#btn-cancel-followups').on('click', function() {
            var keys = $('#w1').yiiGridView('getSelectedRows');
            $('#loader').show();
            $.ajax({
                method: 'POST',
                url: '" . Yii::$app->urlManager->createUrl("inquiry/cancel-pending-followups") . "',
                data: {
                    ids: keys
                },
                success: function(data) {
                    $('#loader').hide();
                    console.log(data);
                    location.reload();
                },
                error: function(data) {
                    $('#loader').hide();
                    console.log('error', data);
                }
            });
        });
    });


");