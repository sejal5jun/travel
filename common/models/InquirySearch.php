<?php

namespace common\models;

use backend\models\enums\InquiryStatusTypes;
use backend\models\enums\UserTypes;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Inquiry;
use yii\data\Sort;

/**
 * InquirySearch represents the model behind the search form about `common\models\Inquiry`.
 */
class InquirySearch extends Inquiry
{
    const PENDING_FOLLOWUPS = 1;
    const TODAYS_FOLLOWUPS = 2;
    const ALL_FOLLOWUPS = 3;
    public $head;
    public $followup_type;

    public $pn;
    public $dest;
    public $pess_date;
    public $pess_mobile;
    public $fu_staff;
    public $quoted_email;
    public $fu_manager;
    public $qu_staff;
    public $qu_manager;
    public $qu_agent;

    public $quoted_returndate;
    public $quoted_nights;
    public $quoted_leaving_from;
    public $quoted_customer_type;
    public $quoted_inquiry_type;
    public $quoted_source;
    public $quoted_inquiry_priority;
    public $qu_head;
    public $quoted_inquiry_id;

    public $booking_staff;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'inquiry_priority', 'inquiry_id', 'type', 'customer_type', 'agent_id', 'adult_count', 'children_count', 'room_count', 'booking_staff','no_of_days', 'inquiry_head', 'quotation_manager', 'follow_up_head', 'follow_up_staff', 'quotation_staff', 'source', 'status', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['qu_head', 'quoted_inquiry_priority', 'quoted_source', 'quoted_inquiry_type', 'quoted_customer_type', 'quoted_leaving_from', 'from_date', 'return_date', 'quoted_nights', 'quoted_returndate', 'qu_manager', 'qu_staff', 'fu_manager', 'qu_agent', 'pn', 'quoted_email', 'dest', 'pess_date', 'pess_mobile', 'fu_staff',
                'name','quoted_inquiry_id', 'email', 'mobile', 'destination', 'notes', 'leaving_from', 'inquiry_details', 'head', 'followup_type', 'reference', 'highly_interested'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $search_criteria = '', $followup_type = '', $priority = '',$customer_type = '',$country= '')
    {


        if (isset($params['InquirySearch']['from_date'])) {
            $str = strtotime($params['InquirySearch']['from_date']);
            // echo $str;exit;
            $params['InquirySearch']['from_date'] = $str;

            $end = $str + 86400;

        } else {
            $str = '';
            $end = '';
        }
        if (isset($params['InquirySearch']['return_date'])) {
            $str_return = strtotime($params['InquirySearch']['return_date']);
            // echo $str;exit;
            $params['InquirySearch']['return_date'] = $str_return;

            $end_return = $str + 86400;

        } else {
            $str_return = '';
            $end_return = '';
        }
        if (isset($params['InquirySearch']['pess_date'])) {
            $quoted_from_str = strtotime($params['InquirySearch']['pess_date']);
            // echo $str;exit;
            $params['InquirySearch']['pess_date'] = $quoted_from_str;

            $quoted_from_end = $quoted_from_str + 86400;

        } else {
            $quoted_from_str = '';
            $quoted_from_end = '';
        }
        if (isset($params['InquirySearch']['quoted_returndate'])) {
            $quoted_return_str = strtotime($params['InquirySearch']['quoted_returndate']);
            // echo $str;exit;
            $params['InquirySearch']['quoted_returndate'] = $quoted_return_str;

            $quoted_return_end = $quoted_return_str + 86400;

        } else {
            $quoted_return_str = '';
            $quoted_return_end = '';
        }

        $today = strtotime('today');
        $query = Inquiry::find();
        if (isset($params['sort'])) {
            if ($search_criteria == InquiryStatusTypes::IN_QUOTATION) {

                if ( $customer_type != "" && $priority != null && $country != "") {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])->joinWith(['inquiryHead']);
                    }
                    else
                    {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                    }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                    }

                    if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination' =>$country])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                    } */

                }
                else if( $customer_type != "" && $priority != null && $country == "")
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->joinWith(['inquiryHead']);
                    }
                    else
                    {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                    }
                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                       $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                           ->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);


                   }

                   if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                       $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                           ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                   }
                   if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                       $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                           ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                   }
                   if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                       $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                           ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                   }*/

                }
                else if ( $customer_type != "" && $priority == "" && $country != "") {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->joinWith(['inquiryHead']);

                    }
                    else
                    {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['quotation_manager'=> Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }*/

                }
                else if( $customer_type != "" && $priority == "" && $country == ""){

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type])->joinWith(['inquiryHead']);
                    }
                    else
                    {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'quotation_manager' => Yii::$app->user->identity->id])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'quotation_manager' => Yii::$app->user->identity->id])
                            ->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                    }

                    if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]]) ->joinWith(['inquiryHead']);
                    }*/
                }
                else{
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                            ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);
                     }*/

                }
            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::PENDING_FOLLOWUPS) {

                if($customer_type !="" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                    if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                           ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }*/
                }
                else if($customer_type !="" && $country == "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }*/


                }
            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::TODAYS_FOLLOWUPS) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }*/
                }
                else if($customer_type != "" && $country == "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                     }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }

                      if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');
                      }*/


                }
            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::ALL_FOLLOWUPS  || $search_criteria == InquiryStatusTypes::QUOTED && $followup_type == '') {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');


                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }*/
                }
                else if($customer_type != "" && $country == "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                      }

                      if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');


                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                      }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');


                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id');

                     }*/

                }
            }
            if ($search_criteria == InquiryStatusTypes::COMPLETED) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->joinWith(['inquiryHead','bookings']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                             ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['bookings']);
                     }*/
                }
                else if($customer_type != "" && $country == "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                            ->joinWith(['inquiryHead','bookings']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings']);

                    }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                              ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead']);

                      }

                      if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead']);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead']);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead']);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type])
                              ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['bookings']);
                      }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                            ->joinWith(['inquiryHead','bookings']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings']);

                    }
                    /*   if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                               ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead']);

                       }

                       if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                               ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead']);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                               ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead']);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                               ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead']);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                               ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['bookings']);
                       }*/

                }


            }
            if ($search_criteria == InquiryStatusTypes::CANCELLED) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);

                    }
                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);

                    }

                    if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['inquiry.follow_up__manager' => Yii::$app->user->identity->id])
                            ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);
                    }*/
                }
                else if($customer_type != "" && $country == "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                             ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                             ->andWhere(['inquiry.follow_up__manager' => Yii::$app->user->identity->id])
                             ->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);
                     }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);

                    }
                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);

                    }

                    if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['inquiry.follow_up__manager' => Yii::$app->user->identity->id])
                            ->joinWith(['inquiryHead']);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead']);
                    }*/


                }


            }
            if($search_criteria == InquiryStatusTypes::VOUCHERED)
            {
                if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                    $query = Inquiry::find()->where(['inquiry.status'=>InquiryStatusTypes::VOUCHERED])->joinWith(['inquiryHead']);
                } else {
                    $query = Inquiry::find()->where(['inquiry.status'=>InquiryStatusTypes::VOUCHERED])
                        ->andWhere(['or', ['inquiry.quotation_manager' => Yii::$app->user->identity->id], ['inquiry.inquiry_head' => Yii::$app->user->identity->id], ['inquiry.quotation_staff' => Yii::$app->user->identity->id], ['inquiry.follow_up_head' => Yii::$app->user->identity->id], ['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead']);

                }
            }

            if ($search_criteria == "") {

                if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                    $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED, InquiryStatusTypes::COMPLETED,InquiryStatusTypes::VOUCHERED]])
                        ->joinWith(['inquiryHead']);

                }
                else{
                    $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED, InquiryStatusTypes::COMPLETED,InquiryStatusTypes::VOUCHERED]])
                        ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                        ->joinWith(['inquiryHead']);
                }
                /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {

                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                         ->joinWith(['inquiryHead']);

                 }

                 if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                         ->joinWith(['inquiryHead']);

                 }
                 if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                         ->joinWith(['inquiryHead']);

                 }
                 if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                         ->joinWith(['inquiryHead']);

                 }*/

            }
        }
        else {

            if ($search_criteria == InquiryStatusTypes::IN_QUOTATION) {

                if ( $customer_type != "" && $priority != null && $country != "") {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                    }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }*/

                }

                else if( $customer_type != "" && $priority != null && $country == ""){

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.inquiry_priority' => $priority,'inquiry.customer_type' => $customer_type])->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }*/
                }
                else if ( $customer_type != "" && $priority == "" && $country != "") {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }*/

                }
                else if($customer_type != "" && $priority == "" && $country == ""){

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->andWhere(['inquiry.customer_type'=>$customer_type])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                            ->andWhere(['inquiry.customer_type'=>$customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                      }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                          $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                              ->andWhere(['inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                      }

                      if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                              ->andWhere(['inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                              ->andWhere(['inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                              ->andWhere(['inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry_head' => Yii::$app->user->identity->id]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                      }*/

                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                    }
                    else{
                        $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                    }
                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);

                     }

                     if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::AMENDED]])
                             ->andWhere(['or',['follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->orderBy(['inquiry.updated_at' => SORT_DESC])->joinWith(['inquiryHead']);
                     }*/
                }
            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::PENDING_FOLLOWUPS) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);
                    }
                    else{

                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }

                    /*   if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                           $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                               ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                           $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                               ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                                ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                           $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                               ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                       }*/
                }
                else if($customer_type != "" && $country == "")
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }

                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }*/

                }
                else
                {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])->joinWith(['inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }

                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['<=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }*/
                }

            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::TODAYS_FOLLOWUPS) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }

                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }*/
                }
                else if($customer_type != "" && $country == "")
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                      }*/

                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['inquiry.inquiry_head'=>yii::$app->user->identity->id])->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                    }

                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['=', 'followup.date', $today])->andWhere(['followup.is_followup' => 0])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                     }*/

                }
            }
            if ($search_criteria == InquiryStatusTypes::QUOTED && $followup_type == $this::ALL_FOLLOWUPS  || $search_criteria == InquiryStatusTypes::QUOTED && $followup_type == '') {
                if($customer_type!= "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }

                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);
                          }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])->andWhere(['followup.is_followup' => 0])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                      }*/
                }
                else if($customer_type!= "" && $country == "")
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }

                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);


                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED, 'inquiry.customer_type' => $customer_type])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.created_at' => SORT_DESC]);

                    }
                    else{
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }

                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Followup::find()
                            ->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);


                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Followup::find()->where(['inquiry.status' => InquiryStatusTypes::QUOTED])->andWhere(['followup.is_followup' => 0])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiry.inquiryHead', 'inquiry'])->groupBy('inquiry.id')->orderBy(['followup.updated_at' => SORT_DESC]);

                    }*/

                }
            }
            if ($search_criteria == InquiryStatusTypes::COMPLETED) {
                if($customer_type != "" && $country !="") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /*if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                            ->joinWith(['bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }*/
                }
                else if($customer_type != "" && $country =="")
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED,'inquiry.customer_type'=>$customer_type])
                              ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }*/
                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead','bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::BOOKING_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::COMPLETED])
                             ->andWhere(['or',['booking.booking_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['bookings'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }*/

                }
            }
            if ($search_criteria == InquiryStatusTypes::CANCELLED) {
                if($customer_type != "" && $country != "") {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /*  if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                              ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                              ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }
                      if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                          $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type,'inquiry.destination'=>$country])
                              ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                              ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                      }*/
                }
                else if($customer_type != "" && $country == "")
                {

                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /*   if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                               ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                               ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                       }
                       if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                           $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED, 'inquiry.customer_type' => $customer_type])
                               ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                               ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                       }*/

                }
                else
                {
                    if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }
                    else{
                        $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                            ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])
                            ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                    }

                    /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                             ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                             ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }
                     if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                         $query = Inquiry::find()->where(['inquiry.status' => InquiryStatusTypes::CANCELLED])
                             ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])
                             ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                     }*/

                }

            }
            if($search_criteria == InquiryStatusTypes::VOUCHERED)
            {

                if (Yii::$app->user->identity->role == UserTypes::ADMIN) {

                    $query = Inquiry::find()->where(['inquiry.status'=>InquiryStatusTypes::VOUCHERED])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                } else {
                    $query = Inquiry::find()->where(['inquiry.status'=>InquiryStatusTypes::VOUCHERED])
                        ->andWhere(['or', ['inquiry.quotation_manager' => Yii::$app->user->identity->id], ['inquiry.inquiry_head' => Yii::$app->user->identity->id], ['inquiry.quotation_staff' => Yii::$app->user->identity->id], ['inquiry.follow_up_head' => Yii::$app->user->identity->id], ['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);

                }
            }
            if ($search_criteria == "") {
                if (Yii::$app->user->identity->role == UserTypes::ADMIN) {
                    $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED, InquiryStatusTypes::COMPLETED,InquiryStatusTypes::VOUCHERED]])
                        ->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                }
                else{
                    $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED, InquiryStatusTypes::COMPLETED,InquiryStatusTypes::VOUCHERED]])
                        ->andWhere(['or',['inquiry.quotation_manager' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id],['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.follow_up_staff' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                }

                /* if (Yii::$app->user->identity->role == UserTypes::QUOTATION_STAFF) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.quotation_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                  }
                 if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_MANAGER) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.follow_up_head' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                  }
                 if (Yii::$app->user->identity->role == UserTypes::FOLLOW_UP_STAFF) {
                     $query = Inquiry::find()->where(['IN', 'inquiry.status', [InquiryStatusTypes::IN_QUOTATION, InquiryStatusTypes::QUOTED, InquiryStatusTypes::AMENDED]])
                         ->andWhere(['or',['inquiry.follow_up_staff' => Yii::$app->user->identity->id],['inquiry.inquiry_head' => Yii::$app->user->identity->id]])->joinWith(['inquiryHead'])->orderBy(['inquiry.updated_at' => SORT_DESC]);
                        }*/
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['head'] = [
             'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pess_date'] = [
             'asc' => ['from_date' => SORT_ASC],
            'desc' => ['from_date' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['quotation_manager'] = [
            'asc' => ['quotation_manager' => SORT_ASC],
            'desc' => ['quotation_manager' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['fu_manager'] = [
            'asc' => ['follow_up_head' => SORT_ASC],
            'desc' => ['follow_up_head' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // echo "<pre>";print_r($this->getErrors());exit;
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'inquiry.inquiry_id' => $this->inquiry_id,
            'inquiry.inquiry_priority' => $this->inquiry_priority,
            'inquiry.type' => $this->type,
            'inquiry.customer_type' => $this->customer_type,
            'inquiry.agent_id' => $this->agent_id,
            'inquiry.adult_count' => $this->adult_count,
            'children_count' => $this->children_count,
            'room_count' => $this->room_count,
            // 'from_date' => $this->from_date,
            //'return_date' => $this->return_date,
            'inquiry.no_of_days' => $this->no_of_days,
            'inquiry.quotation_manager' => $this->quotation_manager,
            'inquiry.inquiry_head' => $this->inquiry_head,
            'inquiry.follow_up_head' => $this->follow_up_head,
            'inquiry.follow_up_staff' => $this->follow_up_staff,
            'inquiry.quotation_staff' => $this->quotation_staff,
            'inquiry.source' => $this->source,
            'inquiry.status' => $this->status,
            'inquiry.highly_interested' => $this->highly_interested,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //followupmodel
            'no_of_days' => $this->quoted_nights,
            'customer_type' => $this->quoted_customer_type,
            'type' => $this->quoted_inquiry_type,
            'source' => $this->quoted_source,
            'inquiry_priority' => $this->quoted_inquiry_priority,
            'inquiry_head' => $this->qu_head,
            'quotation_staff' => $this->qu_staff,
            'quotation_manager' => $this->qu_manager,
            'follow_up_staff' => $this->fu_staff,
            //'follow_up_head' => $this->fu_manager,
            'agent_id' => $this->qu_agent,
            'booking.booking_staff' => $this->booking_staff,
        ]);
    if (isset($params['InquirySearch']['quoted_inquiry_id'])) {
    $query->andFilterWhere(['inquiry.inquiry_id' => $this->quoted_inquiry_id]);
    }
   $query->andFilterWhere(['like', 'inquiry.name', $this->name])
            ->andFilterWhere(['like', 'inquiry.email', $this->email])
            ->andFilterWhere(['like', 'inquiry.mobile', $this->mobile])
            ->andFilterWhere(['like', 'inquiry.notes', $this->notes])
            ->andFilterWhere(['like', 'inquiry.inquiry_details', $this->inquiry_details])
            ->andFilterWhere(['like', 'inquiry.destination', $this->destination])
            ->andFilterWhere(['like', 'inquiry.leaving_from', $this->leaving_from])
            ->andFilterWhere(['like', 'inquiry.reference', $this->reference])
            ///followupmodel

            ->andFilterWhere(['like', 'inquiry.name', $this->pn])
            ->andFilterWhere(['like', 'inquiry.email', $this->quoted_email])
            ->andFilterWhere(['like', 'inquiry.mobile', $this->pess_mobile])
            ->andFilterWhere(['like', 'inquiry.destination', $this->dest])
            ->andFilterWhere(['like', 'inquiry.highly_interested', $this->highly_interested])
            ->andFilterWhere(['like', 'inquiry.leaving_from', $this->quoted_leaving_from])
            //->andFilterWhere(['like', 'inquiry.quotation_staff', $this->qu_staff])
           // ->andFilterWhere(['like', 'inquiry.quotation_manager', $this->qu_manager])
            //->andFilterWhere(['like', 'inquiry.follow_up_staff', $this->fu_staff])
           ->andFilterWhere(['like', 'inquiry.follow_up_head', $this->fu_manager]);
            //->andFilterWhere(['like', 'inquiry.inquiry_id', $this->quoted_inquiry_id]);


        if (isset($params['InquirySearch']['from_date'])) {

            if ($params['InquirySearch']['from_date'] != '') {
                $query->andFilterWhere(['between', 'from_date', $str, $end]);
            }
        }
        if (isset($params['InquirySearch']['return_date'])) {

            if ($params['InquirySearch']['return_date'] != '') {
                $query->andFilterWhere(['between', 'from_date', $str_return, $end_return]);
            }
        }
        if (isset($params['InquirySearch']['pess_date'])) {

            if ($params['InquirySearch']['pess_date'] != "") {

                $query->andFilterWhere(['between', 'from_date', $quoted_from_str, $quoted_from_end]);
            }

        }

        if (isset($params['InquirySearch']['quoted_returndate'])) {

            if ($params['InquirySearch']['quoted_returndate'] != "") {
                $query->andFilterWhere(['between', 'from_date', $quoted_return_str, $quoted_return_end]);
            }

        }

         //echo '<pre>'; print_r($dataProvider->getModels()); exit;
        //echo '<pre>'; print_r($this); exit;
       //echo "<pre>"; print_r($query->createCommand()->rawSql); exit;
        //echo count($dataProvider); exit;
        return $dataProvider;
    }
}
