<?php

use backend\models\enums\InquiryStatusTypes;
use common\models\Booking;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FollowupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="followup-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //Html::a('Create Followup', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



</div>-->



<div class="fill-container bg-white">
    <div class="row">
    <div class="col-sm-4 list-view-label">
       <!-- <label class="control-label">Booking Id</label>-->
        <?= Select2::widget([
            'name' => 'Booking[booking_id]',
            'id' => 'inquiry_booking_id',
            'value' =>'booking_id' , // initial value
            'data' => Booking::getBookingId(),
            'options' => ['placeholder' => 'Select Booking Id'],
            'pluginOptions' => ['tags' => false,
                'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 10],
        ]); ?>
    </div>
    <div class="col-sm-1 pull-right list-view-label">
        <a  href="<?=Yii::$app->getUrlManager()->createUrl(['/inquiry/index', 'type' => InquiryStatusTypes::COMPLETED])?>" ><Strong><i class="icon-list"></i> List View</Strong></a>
    </div>

    </div>
    <div class="relative full-height">


        <div class="display-columns">
           <!-- <div class="column events-sidebar hide bg-default-light">
                <button type="button" class="btn btn-default fc-header-btn add-event">
                    <span class="icon-plus"></span>
                    <span>Add new event</span>
                </button>
                <ul class="external-events p-a" id="external-events">
                    <li>
                        <div class="external-event event-primary" data-class="event-primary">
                            <span>Shopping</span>
                        </div>
                    </li>
                    <li>
                        <div class="external-event event-success" data-class="event-success">
                            <span>Meeting</span>
                        </div>
                    </li>
                    <li>
                        <div class="external-event event-info" data-class="event-info">
                            <span>Recreational</span>
                        </div>
                    </li>
                    <li>
                        <div class="external-event event-warning" data-class="event-warning">
                            <span>Task</span>
                        </div>
                    </li>
                </ul>
            </div>-->

            <div class="column calendar-viewer">
                <div class="fullcalendar scroll">


                </div>
            </div>
       </div>
    </div>
</div>

<?php $this->registerCssFile(Yii::$app->urlManager->baseUrl."/css/vendor/fullcalendar/dist/fullcalendar.min.css",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/moment/min/moment.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/jquery.ui/ui/core.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/jquery.ui/ui/widget.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/jquery.ui/ui/mouse.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/jquery.ui/ui/draggable.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/fullcalendar/dist/fullcalendar.min.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/vendor/fullcalendar/dist/gcal.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>

<?php $this->registerJsFile(Yii::$app->urlManager->baseUrl."/js/apps/calendar.js",['depends' => [\yii\web\JqueryAsset::className()]]);?>

<?php $this->registerJs("
$(document).ready(function(){



$.ajax({
            type:'GET',
            url: '" . Yii::$app->getUrlManager()->createUrl(['booking/get-calender-date']) . "',
            success: function(data) {


         var obj = JSON.parse(data);


$('.fullcalendar').fullCalendar({

 eventLimit: true, // for all non-agenda views

        height: $(window).height() - $('.header').height() - $('.content-footer').height() - 25,
        editable: true,
        defaultView: 'month',
        header: {left: 'today prev,next', right: 'title month'},
        buttonIcons: {prev: ' fa fa-caret-left', next: ' fa fa-caret-right',},
        droppable: true,
        axisFormat: 'h:mm',
      //  columnFormat: {month: 'dddd', week: 'ddd M/D', day: 'dddd M/d', agendaDay: 'dddd D'},
        allDaySlot: false,
        drop: function (date) {
            var originalEventObject = $(this).data('eventObject');
            var copiedEventObject = $.extend({}, originalEventObject);
            copiedEventObject.start = date;
            $('.fullcalendar').fullCalendar('renderEvent', copiedEventObject, true);
            if ($('#drop-remove').is(':checked')) {
                $(this).remove();
            }
        },
        defaultDate: moment().format('YYYY-MM-DD'),
        viewRender: function (view, element) {
            if (!$('.fc-toolbar .fc-left .fc-t-events').length) {
                $('.fc-toolbar .fc-left').prepend($('<button type=\"button\" class=\"fc-button fc-state-default fc-corner-left fc-corner-right fc-t-events\"><i class=\"icon-list\"></i></button>').on('click', function () {
                    $('.events-sidebar').toggleClass('hide');
                }));
            }
        },

        events: obj





    });
 }

        });

        $('#inquiry_booking_id').change(function(){

        var bookingid= $(this).val();
              var url='". Yii::$app->getUrlManager()->createUrl(['booking/view'])."'
          window.location.href= url+'&id='+bookingid ;
       // alert(bookingid);

        });


});

"); ?>
