<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 27-06-2016
 * Time: 17:30
 */
namespace backend\models\enums;

class SourceTypes {
    const PHONE_CALL = 1;
    const SMS = 2;
    const EMAIL = 3;
    const WHATSAPP = 4;
    const SOCIAL_MEDIAL = 5;
    const REFERENCE = 6;

    public static $constants = [
        'phone_call' => self::PHONE_CALL,
        'sms' => self::SMS,
        'email' => self::EMAIL,
        'whats_app' => self::WHATSAPP,
        'social_media' => self::SOCIAL_MEDIAL,
        'reference' => self::REFERENCE
    ];

    public static $titles = [
        self::PHONE_CALL => 'Phone Call',
        self::SMS => 'SMS',
        self::EMAIL => 'Email',
        self::WHATSAPP => 'WhatsApp',
        self::SOCIAL_MEDIAL => 'Social Media',
        self::REFERENCE => 'Reference'
    ];

    public static $headers = [
        self::PHONE_CALL => 'Phone Call',
        self::SMS => 'SMS',
        self::EMAIL => 'Email',
        self::WHATSAPP => 'WhatsApp',
        self::SOCIAL_MEDIAL => 'Social Media',
        self::REFERENCE => 'Reference'
    ];
}