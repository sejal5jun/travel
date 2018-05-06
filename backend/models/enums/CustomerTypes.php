<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 13-07-2016
 * Time: 16:22
 */
namespace backend\models\enums;

class CustomerTypes {
    const CUSTOMER = 1;
    const AGENT = 2;

    public static $constants = [
        'customer' => self::CUSTOMER,
        'agent' => self::AGENT
    ];

    public static $titles = [
        self::CUSTOMER => 'Customer',
        self::AGENT => 'Agent'
    ];

    public static $headers = [
        self::CUSTOMER => 'Customer',
        self::AGENT => 'Agent'
    ];
}