<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agent".
 *
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property City $city
 */
class Agent extends AppActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [[ 'created_at', 'updated_at'], 'integer'],
			[['city_id'],'safe'],
            [['city_id', 'name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'city_id' => 'City',
            'status' => 'Status',
            'created_at' => 'Added On',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * get all agents
     * @param $city_id
     * @return mixed
     */
    public static function getAgent($city_id='')
    {
        $data =  static::find()->where(['status' => 10])->all();
        if($city_id !='')
            $data =  static::find()->where(['city_id' => $city_id,'status' => 10])->all();
        $value = (count($data)==0)? []: \yii\helpers\ArrayHelper::map($data, 'id','name'); //id = your ID model, name = your caption
        return $value;
    }


}
