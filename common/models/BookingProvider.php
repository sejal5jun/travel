<?php
namespace common\models;
use common\models\RecordBooking;
use yii\data\ArrayDataProvider;

/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 13-09-2016
 * Time: 10:16
 */

class BookingProvider extends \yii\data\ArrayDataProvider{

    public $user_id;
    public function init(){
        $query = RecordBooking::find();
        //echo '<pre>';
        foreach($query->all() as $q){
           // print_r($q);
            $m = date('M',$q->month_year);

            $this->allModels[] = [
              'staff'=> $q->user->username,
              'user_id'=> $q->user_id,
                'jan'=> ($m=="Jan" ? '₹ ' .$q->amount: "-"),
                'feb'=>($m=="Feb" ? '₹ ' .$q->amount: "-"),
                'mar'=>($m=="Mar" ? '₹ ' .$q->amount: "-"),
                'apr'=>($m=="Apr" ? '₹ ' . $q->amount: "-"),
                'may'=>($m=="May" ? '₹ ' .$q->amount: "-"),
                'jun'=>($m=="Jun" ? '₹ ' .$q->amount: "-"),
                'jul'=>($m=="Jul" ? '₹ ' .$q->amount: "-"),
                'aug'=>($m=="Aug" ? '₹ ' .$q->amount: "-"),
                'sep'=>($m=="Sep" ? '₹ ' .$q->amount: "-"),
                'oct'=>($m=="Oct" ? '₹ ' .$q->amount: "-"),
                'nov'=>($m=="Nov" ? '₹ ' .$q->amount: "-"),
                'dec'=>($m=="Dec" ? '₹ ' .$q->amount: "-"),

            ];
        }
    }

    public function search($user,$year)
    {
        $query = RecordBooking::find()->all();

        if($user!='' && $year==''){
            $query = RecordBooking::find()->where(['user_id' => $user])->all();
            foreach($query as $q){
                // print_r($q);
                $m = date('M',$q->month_year);

                $model[] = [
                    'staff'=> $q->user->username,
                    'user_id'=> $q->user_id,
                    'jan'=> ($m=="Jan" ? '₹ ' .$q->amount: "-"),
                    'feb'=>($m=="Feb" ? '₹ ' .$q->amount: "-"),
                    'mar'=>($m=="Mar" ? '₹ ' .$q->amount: "-"),
                    'apr'=>($m=="Apr" ? '₹ ' .$q->amount: "-"),
                    'may'=>($m=="May" ? '₹ ' .$q->amount: "-"),
                    'jun'=>($m=="Jun" ? '₹ ' .$q->amount: "-"),
                    'jul'=>($m=="Jul" ? '₹ ' .$q->amount: "-"),
                    'aug'=>($m=="Aug" ? '₹ ' .$q->amount: "-"),
                    'sep'=>($m=="Sep" ? '₹ ' .$q->amount: "-"),
                    'oct'=>($m=="Oct" ? '₹ ' .$q->amount: "-"),
                    'nov'=>($m=="Nov" ? '₹ ' .$q->amount: "-"),
                    'dec'=>($m=="Dec" ? '₹ ' .$q->amount: "-"),

                ];

            }
        }

        else if($year!='' && $user==''){
            foreach($query as $q){
                // print_r($q);
                $m = date('M',$q->month_year);
                $y = date('Y',$q->month_year);

                $model[] = [
                    'staff'=> $q->user->username,
                    'user_id'=> $q->user_id,
                    'jan'=>($m=="Jan" ? $y==$year ? '₹ ' .$q->amount : "-" : '-'),
                    'feb'=>($m=="Feb" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'mar'=>($m=="Mar" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'apr'=>($m=="Apr" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'may'=>($m=="May" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jun'=>($m=="Jun" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jul'=>($m=="Jul" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'aug'=>($m=="Aug" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'sep'=>($m=="Sep" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'oct'=>($m=="Oct" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'nov'=>($m=="Nov" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'dec'=>($m=="Dec" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),

                ];

            }
        }

        else if($year!='' && $user!=''){
            $query = RecordBooking::find()->where(['user_id' => $user])->all();
            foreach($query as $q){
                // print_r($q);
                $m = date('M',$q->month_year);
                $y = date('Y',$q->month_year);

                $model[] = [
                    'staff'=> $q->user->username,
                    'user_id'=> $q->user_id,
                    'jan'=>($m=="Jan" ? $y==$year ? '₹ ' .$q->amount : "-" : '-'),
                    'feb'=>($m=="Feb" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'mar'=>($m=="Mar" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'apr'=>($m=="Apr" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'may'=>($m=="May" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jun'=>($m=="Jun" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jul'=>($m=="Jul" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'aug'=>($m=="Aug" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'sep'=>($m=="Sep" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'oct'=>($m=="Oct" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'nov'=>($m=="Nov" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'dec'=>($m=="Dec" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),

                ];

            }
        }
        else{
            foreach($query as $q){
               $year = date('Y');
                $m = date('M',$q->month_year);
                $y = date('Y',$q->month_year);
                $model[] = [
                    'staff'=> $q->user->username,
                    'user_id'=> $q->user_id,
                    'jan'=>($m=="Jan" ? $y==$year ? '₹ ' .$q->amount : "-" : '-'),
                    'feb'=>($m=="Feb" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'mar'=>($m=="Mar" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'apr'=>($m=="Apr" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'may'=>($m=="May" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jun'=>($m=="Jun" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'jul'=>($m=="Jul" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'aug'=>($m=="Aug" ? $y==$year ? '₹ ' . $q->amount : "-" : "-"),
                    'sep'=>($m=="Sep" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'oct'=>($m=="Oct" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'nov'=>($m=="Nov" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),
                    'dec'=>($m=="Dec" ? $y==$year ? '₹ ' .$q->amount : "-" : "-"),

                ];

            }
        }


        //echo '<pre>';print_r($query);exit;
        return $model;
    }

}