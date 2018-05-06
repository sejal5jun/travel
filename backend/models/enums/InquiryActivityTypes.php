<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 18-08-2016
 * Time: 14:54
 */
namespace backend\models\enums;

class InquiryActivityTypes
{
    const QUOTED = 1;
    const FOLLOWUP = 2;

    public static $constants = [
        'quoted' => self::QUOTED,
        'follow_up' => self::FOLLOWUP
    ];

    public static $titles = [
        self::QUOTED => 'Quoted',
        self::FOLLOWUP => 'Followup'
    ];

    public static $headers = [
        self::QUOTED => 'Quoted',
        self::FOLLOWUP => 'Followup'
    ];
}