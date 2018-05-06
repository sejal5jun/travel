<?php

namespace backend\controllers;

use backend\models\enums\UserTypes;

use common\filters\IpFilter;
use common\models\ActivityCount;
use common\models\ActivityCountSearch;
use common\models\Booking;
use common\models\BookingSearch;
use common\models\RecordBooking;
use common\models\BookingProvider;
use common\models\RecordBookingSearch;
use common\models\RecordInquirySearch;
use common\models\RecordLoginSearch;
use PHPExcel;
use PHPExcel_IOFactory;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => IpFilter::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all activity day wise performance models.
     * @return mixed
     */
    public function actionDayPerformance()
    {
        $s_date = '';
        $e_date = '';
        $user = '';
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->DayPerformanceExport();
        }
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['start_date']))
            $s_date = Yii::$app->request->queryParams['ActivityCountSearch']['start_date'];
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['end_date']))
            $e_date = Yii::$app->request->queryParams['ActivityCountSearch']['end_date'];
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['user_id']))
            $user = Yii::$app->request->queryParams['ActivityCountSearch']['user_id'];
        $searchModel = new ActivityCountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if($s_date=='')
            $s_date = date("M-d-Y");
        if($e_date=='')
            $e_date = date("M-d-Y");
        return $this->render('day_performance', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            's_date' => $s_date,
            'e_date' => $e_date,
            'user' => $user,
        ]);
    }

    /**
     * Lists all activity staff wise performance models.
     * @return mixed
     */
    public function actionStaffPerformance()
    {
        $s_date = '';
        $e_date = '';
        $user = '';
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->StaffPerformanceExport();
        }
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['start_date']))
            $s_date = Yii::$app->request->queryParams['ActivityCountSearch']['start_date'];
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['end_date']))
            $e_date = Yii::$app->request->queryParams['ActivityCountSearch']['end_date'];
        if (isset(Yii::$app->request->queryParams['ActivityCountSearch']['user_id']))
            $user = Yii::$app->request->queryParams['ActivityCountSearch']['user_id'];
        $searchModel = new ActivityCountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('staff_performance', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            's_date' => $s_date,
            'e_date' => $e_date,
            'user' => $user,
        ]);
    }

    /**
     * Lists export to excel of day-performance-report.
     * @return mixed
     */
    public function DayPerformanceExport()
    {
        $searchModel = new ActivityCountSearch();
        //echo '<pre>';print_r(Yii::$app->request->queryParams);exit;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'USER');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ROLE');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'PERFORMANCE');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'DATE');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $dataProvider[$i - 2]->user->username);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, UserTypes::$headers[$dataProvider[$i - 2]->user->role]);
            if($dataProvider[$i - 2]->user->role==UserTypes::FOLLOW_UP_MANAGER || $dataProvider[$i - 2]->user->role==UserTypes::FOLLOW_UP_STAFF)
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Followup Taken: '. $dataProvider[$i - 2]->followup_count);
            else if($dataProvider[$i - 2]->user->role==UserTypes::QUOTATION_STAFF || $dataProvider[$i - 2]->user->role==UserTypes::QUOTATION_MANAGER)
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Quotation Sent: ' . $dataProvider[$i - 2]->quotation_count);
            else if($dataProvider[$i - 2]->user->role==UserTypes::ADMIN)
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Quotation Sent: ' . $dataProvider[$i - 2]->quotation_count . ' ' . 'Followup Taken: '. $dataProvider[$i - 2]->followup_count);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, date("M-d-Y",$dataProvider[$i - 2]->date));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Day Wise Performance Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Performance Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * Lists export to excel of staff-performance-report.
     * @return mixed
     */
    public function StaffPerformanceExport()
    {
        $searchModel = new ActivityCountSearch();
        //echo '<pre>';print_r(Yii::$app->request->queryParams);exit;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'USER');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ROLE');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PERFORMANCE');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, date("M-d-Y",$dataProvider[$i - 2]->date));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $dataProvider[$i - 2]->user->username);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, UserTypes::$headers[$dataProvider[$i - 2]->user->role]);
            if($dataProvider[$i - 2]->user->role==UserTypes::FOLLOW_UP_MANAGER || $dataProvider[$i - 2]->user->role==UserTypes::FOLLOW_UP_STAFF)
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, 'Followup Taken: '. $dataProvider[$i - 2]->followup_count);
            else if($dataProvider[$i - 2]->user->role==UserTypes::QUOTATION_STAFF || $dataProvider[$i - 2]->user->role==UserTypes::QUOTATION_MANAGER)
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, 'Quotation Sent: ' . $dataProvider[$i - 2]->quotation_count);
            else if($dataProvider[$i - 2]->user->role==UserTypes::ADMIN)
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, 'Quotation Sent: ' . $dataProvider[$i - 2]->quotation_count . ' ' . 'Followup Taken: '. $dataProvider[$i - 2]->followup_count);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Staff Wise Performance Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Performance Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * Lists day wise login record models.
     * @return mixed
     */
    public function actionDayLogin()
    {
        $user = '';
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->DayLoginExport();
        }
        else{
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['start_date']))
                $s_date = Yii::$app->request->queryParams['RecordLoginSearch']['start_date'];
            else
                $s_date = date("M-d-Y");
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['end_date']))
                $e_date = Yii::$app->request->queryParams['RecordLoginSearch']['end_date'];
            else
                $e_date = date("M-d-Y");
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['user_id']))
                $user = Yii::$app->request->queryParams['RecordLoginSearch']['user_id'];
            $searchModel = new RecordLoginSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('day_login_index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                's_date' => $s_date,
                'e_date' => $e_date,
                'user' => $user,
            ]);
        }

    }

    /**
     * Lists export to excel of day-wise-login-report.
     * @return mixed
     */
    public function DayLoginExport()
    {
        $searchModel = new RecordLoginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'USER');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'LOGIN TIME');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $dataProvider[$i - 2]->user->username);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, Yii::$app->formatter->asDatetime($dataProvider[$i - 2]->login_time));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Login Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Login Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * Lists staff wise login record models.
     * @return mixed
     */
    public function actionStaffLogin()
    {
        $s_date = '';
        $e_date = '';
        $user = '';
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->StaffLoginExport();
        }
        else{
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['start_date']))
                $s_date = Yii::$app->request->queryParams['RecordLoginSearch']['start_date'];
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['end_date']))
                $e_date = Yii::$app->request->queryParams['RecordLoginSearch']['end_date'];
            if (isset(Yii::$app->request->queryParams['RecordLoginSearch']['user_id']))
                $user = Yii::$app->request->queryParams['RecordLoginSearch']['user_id'];
            $searchModel = new RecordLoginSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('staff_login_index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                's_date' => $s_date,
                'e_date' => $e_date,
                'user' => $user,
            ]);
        }

    }

    /**
     * Lists export to excel of staff-wise-login-report.
     * @return mixed
     */
    public function StaffLoginExport()
    {
        $searchModel = new RecordLoginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'USER');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'LOGIN TIME');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $dataProvider[$i - 2]->user->username);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, Yii::$app->formatter->asDatetime($dataProvider[$i - 2]->login_time));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Login Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Login Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * Lists booking record models.
     * @return mixed
     */
    public function actionBookingReport()
    {
        $year = '';
        $user = '';
        $searchModel = new RecordBookingSearch();
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if (isset(Yii::$app->request->queryParams['RecordBookingSearch']['user_id']))
            $user = Yii::$app->request->queryParams['RecordBookingSearch']['user_id'];
        if (isset(Yii::$app->request->queryParams['RecordBookingSearch']['year']))
            $year = Yii::$app->request->queryParams['RecordBookingSearch']['year'];
        if(isset($export) && !isset($search)) {
            $this->BookingExport($user,$year);
        }
        else{
            $q = new BookingProvider();
            $filterData = $q->search($user,$year);
            $provider = new ArrayDataProvider([
                'allModels' =>$filterData,
                'sort' => [
                    'attributes' => ['staff', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            return $this->render('booking_index', [
                'searchModel' => $searchModel,
                'provider' => $provider,
                'year' => $year,
                'user' => $user,
            ]);
        }

    }

    /**
     * Lists export to excel of booking-report.
     * @params $user,$year
     * @return mixed
     */
    public function BookingExport($user,$year)
    {
        $q = new BookingProvider();
        $filterData = $q->search($user,$year);
        $count = count($filterData);
        //echo $count;exit;
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'STAFF');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'JAN');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'FEB');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'MAR');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'APR');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'MAY');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'JUN');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'JUL');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'AUG');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'SEP');
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'OCT');
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'NOV');
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'DEC');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $filterData[$i - 2]['staff']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $filterData[$i - 2]['jan']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $filterData[$i - 2]['feb']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $filterData[$i - 2]['mar']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $filterData[$i - 2]['apr']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $filterData[$i - 2]['may']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $filterData[$i - 2]['jun']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $filterData[$i - 2]['jul']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $filterData[$i - 2]['aug']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $filterData[$i - 2]['sep']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $filterData[$i - 2]['oct']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $filterData[$i - 2]['nov']);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $filterData[$i - 2]['dec']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Booking Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "Booking Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    /**
     * Lists day inquiry record models.
     * @return mixed
     */
    public function actionInquiryReport()
    {
        $s_date='';
        $e_date='';
        $export = Yii::$app->request->get('export');
        $search = Yii::$app->request->get('search');
        if(isset($export) && !isset($search)) {
            $this->InquiryExport();
        }
        else{
            if (isset(Yii::$app->request->queryParams['BookingSearch']['start_date']))
                $s_date = Yii::$app->request->queryParams['BookingSearch']['start_date'];
            if (isset(Yii::$app->request->queryParams['BookingSearch']['end_date']))
                $e_date = Yii::$app->request->queryParams['BookingSearch']['end_date'];
            if($s_date=='')
                $s_date = date("M-d-Y");
            if($e_date=='')
                $e_date = date("M-d-Y");
            $searchModel = new RecordInquirySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('inquiry_index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                's_date' => $s_date,
                'e_date' => $e_date,
            ]);
        }

    }

    /**
     * Lists export to excel of day-inquiry-report.
     * @return mixed
     */
    public function InquiryExport()
    {
        $searchModel = new RecordInquirySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->query->all();
        $count = count($dataProvider);
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'DATE');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'INQUIRY ADDED');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'QUOTATION SENT');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'FOLLOWUPS TAKEN');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'BOOKINGS');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CANCELLED INQUIRIES');

        for ($i = 2; $i < $count + 2; $i++) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, Yii::$app->formatter->asDate($dataProvider[$i - 2]->date));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $dataProvider[$i - 2]->new_inquiry_count);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $dataProvider[$i - 2]->quotation_count);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $dataProvider[$i - 2]->followup_count);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $dataProvider[$i - 2]->booking_count);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $dataProvider[$i - 2]->cancellation_count);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inquiry Report');

// Redirect output to a client’s web browser (Excel5)
        $filename = "inquiry Report" . date('m-d-Y_his') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


}
