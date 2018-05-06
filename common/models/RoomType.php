<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "room_type".
 *
 * @property integer $id
 * @property string $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class RoomType extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['created_at', 'updated_at'], 'integer'],
            [['type'], 'string', 'max' => 255],
            [['type'], 'required'],
           // [['type'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getRoomTypes()
    {
        $data=  static::find()->where([ 'status' => 10])->all();
        $value=(count($data)==0)? []: \yii\helpers\ArrayHelper::map($data,'type','type');
        return $value;
    }
}
