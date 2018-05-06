<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "itinerary".
 *
 * @property integer $id
 * @property integer $package_id
 * @property integer $media_id
 * @property string $name
 * @property integer $no_of_itineraries
 * @property string $title
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Media $media
 * @property Package $package
 */
class Itinerary extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'itinerary';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['package_id', 'no_of_itineraries', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description'], 'string'],
            //[['media_id'], 'safe'],
            [['title'], 'string', 'max' => 255],
           // [['no_of_itineraries', 'name'], 'required'],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_id' => 'Package ID',
            'media_id' => 'Media ID',
            'name' => 'Name',
            'no_of_itineraries' => 'No Of Nights',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Added On',
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
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }
}
