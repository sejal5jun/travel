<?php
namespace common\models;

use Yii;

class AppActiveRecord extends \yii\db\ActiveRecord {
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }
}