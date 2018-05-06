<?php
namespace common\rbac;

use Yii;
use yii\rbac\Rule;


class UserGroupRule extends Rule {
    public $name = 'userGroup';

    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(!Yii::$app->user->isGuest) {
            $group = Yii::$app->user->identity->role;
            if($item->name == 'admin') {
                return $group == 1;
            } elseif($item->name == 'inquiry_collector') {
                return $group == 1 || $group == 2;
            }  elseif($item->name == 'quotation_manager') {
                return $group == 1 || $group == 2 || $group == 3;
            } elseif($item->name == 'follow_up_manager') {
                return $group == 1 || $group == 2 || $group == 3 || $group == 4;
            } elseif($item->name == 'quotation_staff') {
                return $group == 1 || $group == 2 || $group == 3 || $group == 4 || $group == 5;
            }elseif($item->name == 'follow_up_staff') {
                return $group == 1 || $group == 2 || $group == 3 || $group == 4 || $group == 5 || $group == 6;
            }elseif($item->name == 'booking_staff') {
                return $group == 1 || $group == 2 || $group == 3 || $group == 4 || $group == 5 || $group == 6 || $group == 7;
            }
        }
        return false;
    }
}