<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quotation_itinerary".
 *
 * @property integer $id
 * @property integer $quotation_id
 * @property string $name
 * @property integer $no_of_itineraries
 * @property string $title
 * @property string $description
 * @property integer $media_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Media $media
 * @property InquiryPackage $quotation
 */
class QuotationItinerary extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quotation_itinerary';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['quotation_id', 'no_of_itineraries', 'media_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['quotation_id'], 'required'],
            [['quotation_id'], 'exist', 'skipOnError' => true, 'targetClass' => InquiryPackage::className(), 'targetAttribute' => ['quotation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quotation_id' => 'Quotation ID',
            'media_id' => 'Media ID',
            'name' => 'Name',
            'no_of_itineraries' => 'No Of Itineraries',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotation()
    {
        return $this->hasOne(InquiryPackage::className(), ['id' => 'quotation_id']);
    }
}
