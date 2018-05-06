<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 23-06-2016
 * Time: 15:56
 */
namespace backend\models\enums;


class UserTypes {
    const ADMIN = 1;
    const INQUIRY_HEAD = 2;
    const QUOTATION_MANAGER = 3;
    const FOLLOW_UP_MANAGER = 4;
    const QUOTATION_STAFF = 5;
    const FOLLOW_UP_STAFF = 6;
    const BOOKING_STAFF = 7;

    public static $constants = [
        'admin' => self::ADMIN,
        'inquiry_head' => self::INQUIRY_HEAD,
        'quotation_manager' => self::QUOTATION_MANAGER,
        'follow_up_manager' => self::FOLLOW_UP_MANAGER,
        'quotation_staff' => self::QUOTATION_STAFF,
        'follow_up_staff' => self::FOLLOW_UP_STAFF,
        'booking_staff' => self::BOOKING_STAFF
    ];

    public static $titles = [
        self::ADMIN => 'Admin',
       self::INQUIRY_HEAD => 'Inquiry Head',
        self::QUOTATION_MANAGER => 'Quotation Manager',
        self::FOLLOW_UP_MANAGER => 'Follow Up Manager',
        self::QUOTATION_STAFF => 'Quotation Staff',
        self::FOLLOW_UP_STAFF => 'Follow Up Staff',
        self::BOOKING_STAFF => 'Booking Staff'
    ];

    public static $headers = [
        self::ADMIN => 'Admin',
       self::INQUIRY_HEAD => 'Inquiry Head',
        self::QUOTATION_MANAGER => 'Quotation Manager',
        self::FOLLOW_UP_MANAGER => 'Follow Up Manager',
        self::QUOTATION_STAFF => 'Quotation Staff',
        self::FOLLOW_UP_STAFF => 'Follow Up Staff',
        self::BOOKING_STAFF => 'Booking Staff'
    ];
}