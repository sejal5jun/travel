<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ip_restrictions".
 *
 * @property integer $id
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class IpRestrictions extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ip_restrictions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 255],
            [['ip'], 'required'],
            [['ip'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip Address',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
