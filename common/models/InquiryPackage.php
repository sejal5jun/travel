<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inquiry_package".
 *
 * @property integer $id
 * @property integer $inquiry_id
 * @property integer $package_id
 * @property integer $is_itinerary
 * @property integer $from_date
 * @property integer $return_date
 * @property integer $no_of_nights
 * @property integer $adult_count
 * @property integer $children_count
 * @property integer $room_count
 * @property integer $category
 * @property string $package_name
 * @property string $itinerary_name
 * @property string $no_of_days_nights
 * @property string $validity
 * @property string $till_validity
 * @property string $package_include
 * @property string $package_exclude
 * @property string $passenger_name
 * @property string $passenger_email
 * @property string $email_cc
 * @property string $passenger_mobile
 * @property string $destination
 * @property string $leaving_from
 * @property string $notes
 * @property string $inquiry_details
 * @property string $hotel_details
 * @property string $quotation_details
 * @property string $terms_and_conditions
 * @property string $other_info
 * @property string $pricing_details
 * @property integer $is_quotation_sent
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Followup[] $followups
 * @property Inquiry $inquiry
 * @property Package $package
 * @property InquiryPackageChildAge[] $inquiryPackageChildAges
 * @property InquiryPackageRoomType[] $inquiryPackageRoomTypes
 * @property QuotationItinerary[] $quotationItineraries
 * @property QuotationPricing[] $quotationPricings
 */
class InquiryPackage extends AppActiveRecord
{
    const WITHOUT_ITINERARY = 0;
    const WITH_ITINERARY = 1;
    const QUOTATION_NOT_SENT = 0;
    const QUOTATION_SENT = 1;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inquiry_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['is_itinerary', 'default', 'value' => self::WITH_ITINERARY],
            ['is_quotation_sent', 'default', 'value' => self::QUOTATION_SENT],
            ['is_quotation_sent', 'in', 'range' => [self::QUOTATION_NOT_SENT, self::QUOTATION_SENT]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['inquiry_id', 'package_id','is_itinerary', 'adult_count', 'children_count', 'room_count', 'category', 'created_at', 'updated_at'], 'integer'],
            [['notes', 'validity','till_validity','pricing_details','terms_and_conditions', 'other_info','package_include', 'package_exclude', 'inquiry_details', 'hotel_details', 'quotation_details', 'leaving_from', 'itinerary_name'], 'string'],
            [['passenger_name', 'destination', 'package_name'], 'string', 'max' => 255],
            [['passenger_email'], 'email'],
            [['inquiry_id'], 'required'],
            [[ 'from_date', 'return_date','email_cc', 'no_of_nights'],'safe'],
            [['passenger_mobile'], 'string', 'max' => 15],
            [['no_of_days_nights'], 'string', 'max' => 45],

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
            'package_id' => 'Package ID',
            'is_itinerary'=>'Is Itinerary',
            'from_date' => 'From Date',
            'return_date' => 'Return Date',
            'no_of_nights' => 'No Of Nights',
            'adult_count' => 'Adult Count',
            'children_count' => 'Children Count',
            'room_count' => 'Room Count',
            'passenger_name' => 'Passenger Name',
            'passenger_email' => 'Passenger Email',
            'passenger_mobile' => 'Passenger Mobile',
            'destination' => 'Destination',
            'leaving_from' => 'Leaving From',
            'notes' => 'Notes',
            'inquiry_details' => 'Inquiry Details',
            'hotel_details' => 'Hotel Details',
            'quotation_details' => 'Quotation Details',
            'is_quotation_sent' => 'Is Quotation Sent',
            'package_name' => 'Package Name',
            'itinerary_name' => 'Itinerary Name',
            'no_of_days_nights' => 'No Of Nights/Days',
            'category' => 'Category',
            'package_include' => 'Package Include',
            'package_exclude' => 'Package Exclude',
            'terms_and_conditions' => 'Terms And Conditions',
            'other_info' => 'Other Info',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowups()
    {
        return $this->hasMany(Followup::className(), ['inquiry_package_id' => 'id']);
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
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackageChildAges()
    {
        return $this->hasMany(InquiryPackageChildAge::className(), ['inquiry_package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInquiryPackageRoomTypes()
    {
        return $this->hasMany(InquiryPackageRoomType::className(), ['inquiry_package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationItineraries()
    {
        return $this->hasMany(QuotationItinerary::className(), ['quotation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationPricings()
    {
        return $this->hasMany(QuotationPricing::className(), ['quotation_id' => 'id']);
    }
}