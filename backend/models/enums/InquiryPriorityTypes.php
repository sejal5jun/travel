<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 8/8/2016
 * Time: 4:04 PM
 */


namespace backend\models\enums;

class InquiryPriorityTypes {
    const HOT_NEW_CUSTOMER= 1;
    const HOT_OLD_CUSTOMER = 2;
    const GENERAL_NEW_CUSTOMER = 3;
    const GENERAL_OLD_CUSTOMER = 4;


    public static $constants = [
        'Hot_New_customer' => self::HOT_NEW_CUSTOMER,
        'Hot_Old_customer' => self::HOT_OLD_CUSTOMER,
        'General_New_customer' => self::GENERAL_NEW_CUSTOMER,
        'General_Old_customer' => self::GENERAL_OLD_CUSTOMER,

    ];



    public static $headers = [
        self::HOT_NEW_CUSTOMER => 'Hot New Customer',
        self::HOT_OLD_CUSTOMER => 'Hot Old Customer',
        self::GENERAL_NEW_CUSTOMER => 'General New Customer',
        self::GENERAL_OLD_CUSTOMER => 'General Old Customer',

    ];


}