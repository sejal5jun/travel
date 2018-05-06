<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "booking".
 *
 * @property integer $id
 * @property integer $booking_id
 * @property integer $inquiry_id
 * @property integer $inquiry_package_id
 * @property integer $currency_id
 * @property string $final_price
 * @property string $inr_rate
 * @property string $booking_staff
 *  @property integer $voucher_currency_id
 * @property string $voucher_inr_rate
 * @property string $voucher_final_price
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Inquiry $inquiry
 * @property InquiryPackage $inquiryPackage
 * @property Currency $currency
 *   @property User $bookingStaff
 */
class Booking extends AppActiveRecord
{
	
	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
	
	public $booking_links;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		    ['status', 'default', 'value' => self::STATUS_ACTIVE],
		    ['inr_rate', 'default', 'value' => 1],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_id', 'booking_staff','voucher_currency_id','inquiry_package_id', 'currency_id', 'created_at', 'updated_at'], 'integer'],
            [['inquiry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inquiry::className(), 'targetAttribute' => ['inquiry_id' => 'id']],
			[['booking_id', 'voucher_inr_rate', 'voucher_final_price'],'string'],
			[['final_price', 'inr_rate'],'number'],
			//[['final_price'],'required'],
            [['inquiry_package_id'], 'exist', 'skipOnError' => true, 'targetClass' => InquiryPackage::className(), 'targetAttribute' => ['inquiry_package_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'booking_id' => 'Booking ID',
            'inquiry_id' => 'Inquiry ID',
            'inquiry_package_id' => 'Inquiry Package ID',
            'currency_id' => 'Currency',
            'inr_rate' => 'INR Rate',
            'final_price' => 'Final Price',
            'voucher_currency_id' => 'Currency',
            'voucher_inr_rate' => 'INR Rate',
            'voucher_final_price' => 'Final Price',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookingStaff()
    {
        return $this->hasOne(User::className(), ['id' => 'booking_staff']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public static function getBookingId()
    {
        $data =  static::find()->where(['status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','booking_id'); //id = your ID model, name = your caption
        return $value;
    }
	

}
