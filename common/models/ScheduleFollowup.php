<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "schedule_followup".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $inquiry_package_id
 * @property string $passenger_email
 * @property string $text_body
 * @property integer $scheduled_at
 * @property integer $scheduled_by
 * @property integer $is_sent
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $scheduledBy
 * @property InquiryPackage $inquiryPackage
 * @property Inquiry $inquiry
 */
class ScheduleFollowup extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const NOT_SENT = 0;
    const SENT = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule_followup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['is_sent', 'default', 'value' => self::NOT_SENT],
            ['is_sent', 'in', 'range' => [self::NOT_SENT, self::SENT]],
            [['inquiry_id', 'inquiry_package_id','scheduled_by', 'created_at', 'updated_at'], 'integer'],
            [['text_body'], 'string'],
            [['scheduled_at'], 'safe'],
            [['passenger_email'], 'string', 'max' => 255],
            [['scheduled_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['scheduled_by' => 'id']],
            [['inquiry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inquiry::className(), 'targetAttribute' => ['inquiry_id' => 'id']],
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
            'passenger_email' => 'Passenger Email',
            'text_body' => 'Text Body',
            'scheduled_at' => 'Scheduled At',
            'scheduled_by' => 'Scheduled By',
            'is_sent' => 'Is Sent',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScheduledBy()
    {
        return $this->hasOne(User::className(), ['id' => 'scheduled_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiry()
    {
        return $this->hasOne(Inquiry::className(), ['id' => 'inquiry_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackage()
    {
        return $this->hasOne(InquiryPackage::className(), ['id' => 'inquiry_package_id']);
    }
}
