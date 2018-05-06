<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "record_inquiry".
 *
 * @property integer $id
 * @property integer $new_inquiry_count
 * @property integer $quotation_count
 * @property integer $followup_count
 * @property integer $booking_count
 * @property integer $cancellation_count
 * @property integer $date
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class RecordInquiry extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record_inquiry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['date'], 'safe'],
            [['new_inquiry_count', 'quotation_count', 'followup_count', 'booking_count', 'cancellation_count', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'new_inquiry_count' => 'New Inquiry Count',
            'quotation_count' => 'Quotation Count',
            'followup_count' => 'Followup Count',
            'booking_count' => 'Booking Count',
            'cancellation_count' => 'Cancellation Count',
            'date' => 'Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
