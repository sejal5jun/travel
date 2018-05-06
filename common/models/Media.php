<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property integer $media_type
 * @property string $alt
 * @property string $file_name
 * @property string $file_type
 * @property integer $file_size
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Itinerary[] $itineraries
 * @property QuotationItinerary[] $quotationItineraries
 * @property User[] $users
 */
class Media extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_type', 'file_size', 'created_at', 'updated_at'], 'integer'],
            [['alt', 'file_name'], 'string', 'max' => 255],
            [['file_type'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'media_type' => 'Media Type',
            'alt' => 'Alt',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'file_size' => 'File Size',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItineraries()
    {
        return $this->hasMany(Itinerary::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuotationItineraries()
    {
        return $this->hasMany(QuotationItinerary::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['media_id' => 'id']);
    }
}