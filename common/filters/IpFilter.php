<?php
namespace common\filters;

use Yii;
use yii\filters\AccessControl;
use common\models\IpRestrictions;

class IpFilter extends AccessControl
{
    private $_ips;

    public function init()
    {
        $ipRestrictions = IpRestrictions::find()->select('ip')->asArray()->all();
        $_ips = explode(',', implode(",", array_column($ipRestrictions, 'ip')));

        $rules = [
                [
                    'actions' => ['login', 'error', 'logout'],
                    'allow' => true,
                    'ips' => ['*'],
                ],
                [
                    'allow' => true,
                    'roles' => ['admin'],
                    'ips' => ['*'],
                ],
                [
                    'allow' => true,
                    'roles' => ['admin', 'quotation_manager', 'follow_up_manager', 'quotation_staff', 'follow_up_staff', 'booking_staff'],
                    'ips' => $_ips,//['27.4.*','122.170.*','112.134.87.108','182.48.238.21','127.0.0.1'],
                ],
                [
                    'allow' => false,
                    'ips' => ['*'],
                    'roles' => ['quotation_manager', 'follow_up_manager', 'quotation_staff', 'follow_up_staff', 'booking_staff'],
                    'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You are not allowed to access this page.'));
                    }
                ],
            ];

        $this->rules = array_merge($rules, $this->rules);
        parent::init();
    }
}