<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property string $inquiry_priority
 * @property string $reference
 * @property integer $type
 * @property integer $customer_type
 * @property integer $agent_id
 * @property string $destination
 * @property string $leaving_from
 * @property string $notes
 * @property string $inquiry_details
 * @property integer $adult_count
 * @property integer $children_count
 * @property integer $room_count
 * @property integer $from_date
 * @property integer $return_date
 * @property integer $no_of_days
 * @property integer $quotation_manager
 * @property integer $inquiry_head
 * @property integer $follow_up_head
 * @property integer $follow_up_staff
 * @property integer $quotation_staff
 * @property integer $source
 * @property integer $status
 * @property integer $highly_interested
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $inquiryHead
 * @property User $quotationManager
 * @property User $followUpHead
 * @property User $followUpStaff
 * @property User $quotationStaff
 * @property User $creator
 * @property Agent $agent
 * @property Booking[] $bookings
 * @property Followup[] $followups
 * @property InquiryChildAge[] $inquiryChildAges
 * @property InquiryPackage[] $inquiryPackages
 * @property InquiryRoomType[] $inquiryRoomTypes
 * @property RecordActivity[] $recordActivities
 * @property ScheduleFollowup[] $scheduleFollowups
 * @property InquiryActivity[] $inquiryActivities
 */
class Inquiry extends AppActiveRecord
{
    public $travelling_details;
    public $date_with_days;

    const NOT_HIGHLY_INTERESTED = 0;
    const HIGHLY_INTERESTED = 1;


    public static $headers = [
        self::NOT_HIGHLY_INTERESTED => 'Not Highly Interested',
        self::HIGHLY_INTERESTED => 'Highly Interested'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['inquiry_priority','type', 'inquiry_id','adult_count', 'children_count', 'room_count', 'customer_type', 'agent_id', 'no_of_days', 'quotation_manager', 'inquiry_head', 'follow_up_head', 'quotation_staff' , 'follow_up_staff', 'source', 'status', 'highly_interested', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['name', 'email', 'destination','leaving_from', 'reference'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 15],
            [['email'], 'email'],
            [['from_date', 'return_date'],'safe'],
            [['notes', 'inquiry_details'], 'string'],
            [['name', 'email', 'mobile', 'destination', 'leaving_from', 'type', 'customer_type', 'quotation_manager', 'source', 'inquiry_head', 'from_date','return_date', 'follow_up_head', 'status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inquiry_id' => 'Inquiry ID',
            'name' => 'Passenger Name',
            'email' => 'Passenger Email',
            'mobile' => 'Passenger Mobile',
            'inquiry_priority' => 'Inquiry Priority',
            'type' => 'Inquiry Type',
            'customer_type' => 'Customer Type',
            'agent_id' => 'Agent',
            'destination' => 'Destination',
            'leaving_from' => 'Leaving From',
            'adult_count' => 'Adult Count',
            'children_count' => 'Children Count',
            'room_count' => 'No. of Rooms',
            'from_date' => 'From Date',
            'return_date' => 'Return Date',
            'no_of_days' => 'No Of Nights',
            'quotation_manager' => 'Quotation Manager',
            'inquiry_head' => 'Inquiry Head',
            'follow_up_head' => 'Follow Up Head',
            'follow_up_staff' => 'Follow Up Staff',
            'quotation_staff' => 'Quotation Staff',
            'notes' => 'Notes',
            'source' => 'Source',
            'reference' => 'Reference',
            'inquiry_details' => 'Inquiry Details',
            'status' => 'Status',
            'highly_interested' => 'Highly Interested',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryHead()
    {
        return $this->hasOne(User::className(), ['id' => 'inquiry_head']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationManager()
    {
        return $this->hasOne(User::className(), ['id' => 'quotation_manager']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowUpHead()
    {
        return $this->hasOne(User::className(), ['id' => 'follow_up_head']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowUpStaff()
    {
        return $this->hasOne(User::className(), ['id' => 'follow_up_staff']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationStaff()
    {
        return $this->hasOne(User::className(), ['id' => 'quotation_staff']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowups()
    {
        return $this->hasMany(Followup::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryChildAges()
    {
        return $this->hasMany(InquiryChildAge::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackages()
    {
        return $this->hasMany(InquiryPackage::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryRoomTypes()
    {
        return $this->hasMany(InquiryRoomType::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecordActivities()
    {
        return $this->hasMany(RecordActivity::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheduleFollowups()
    {
        return $this->hasMany(ScheduleFollowup::className(), ['inquiry_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryActivities()
    {
        return $this->hasMany(InquiryActivity::className(), ['inquiry_id' => 'id']);
    }


    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'inquiry_id',
            'type',
            'inquiry_priority',
            'customer_type',
            'agent_id',
            'adult_count',
            'children_count',
            'room_count',
            'from_date',
            'return_date',
            'no_of_days',
            'quotation_manager',
            'inquiry_head',
            'follow_up_head',
            'follow_up_staff',
            'quotation_staff',
            'source',
            'reference',
            'name',
            'mobile',
            'email',
            'destination',
            'leaving_from',
            'notes',
            'inquiry_details',
            'created_by',
            'created_at',
            'updated_at',
            'status',
            // field name is "name", its value is defined by a PHP callback


            'travelling_details' => function ($model) {
                return $model->travelling_details = $model->leaving_from . ' - ' . $model->destination;
            },

            'date_with_days' => function ($model) {
                return $model->date_with_days = date('M-d-Y',$model->from_date) . "\n(" . $model->no_of_days . ' Nights)';
            },

        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->travelling_details = $this->leaving_from . ' - ' . $this->destination;
        $this->date_with_days =  date('M-d-Y',$this->from_date) . "\n(" . $this->no_of_days . ' Nights)';

    }
    public static function getQuotedInquires()
    {
        $data=  static::find()->where(['status' => 2])->all();

        $value=(count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'inquiry_id','inquiry_id');
        return $value;

    }
    public static function getInquiryId()
    {
        $data=  static::find()->all();

        $value=(count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'inquiry_id','inquiry_id');
        return $value;

    }


   /* public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->created_by = Yii::$app->user->id;
            return true;
        } else {
            return false;
        }
    }*/
}
