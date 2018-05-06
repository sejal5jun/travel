<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 20-04-2016
 * Time: 12:54
 */

namespace console\controllers;

use common\rbac\UserGroupRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller {
    public function actionInit() {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Create Permission to create user
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create User';
        $auth->add($createUser);

        // Create Permission to update user
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update User';
        $auth->add($updateUser);

        // Create Permission to delete user
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete User';
        $auth->add($deleteUser);

        // Apply the rule
        $rule = new UserGroupRule();
        $auth->add($rule);

        // Create roles and apply permissions and rules

        // follow_up_manager role
        $follow_up_manager = $auth->createRole('follow_up_manager');
        $follow_up_manager->ruleName = $rule->name;
        $auth->add($follow_up_manager);

        // quotation_manager role
        $quotation_manager = $auth->createRole('quotation_manager');
        $quotation_manager->ruleName = $rule->name;
        $auth->add($quotation_manager);

        // quotation_staff role
        $quotation_staff = $auth->createRole('quotation_staff');
        $quotation_staff->ruleName = $rule->name;
        $auth->add($quotation_staff);

        // follow_up_staff role
        $follow_up_staff = $auth->createRole('follow_up_staff');
        $follow_up_staff->ruleName = $rule->name;
        $auth->add($follow_up_staff);

        // booking_staff role
        $booking_staff = $auth->createRole('booking_staff');
        $booking_staff->ruleName = $rule->name;
        $auth->add($booking_staff);

        // admin role
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $follow_up_manager); // follow_up_manager is child of admin
        $auth->addChild($admin, $quotation_manager); // quotation_manager is child of admin
        $auth->addChild($admin, $quotation_staff); // quotation_staff is child of admin
        $auth->addChild($admin, $follow_up_staff); // follow_up_staff is child of admin
        $auth->addChild($admin, $booking_staff); // booking_staff is child of admin
        $auth->addChild($admin, $createUser); // admin can create user
        $auth->addChild($admin, $updateUser); // admin can update user
        $auth->addChild($admin, $deleteUser); // admin can delete user
    }
}