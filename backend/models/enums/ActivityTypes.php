<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 28-06-2016
 * Time: 16:33
 */
namespace backend\models\enums;

class ActivityTypes {
    const ADDED = 1;
    const UPDATED = 2;
    const QUOTED = 3;
    const FOLLOWED_UP = 4;
    const AMENDED = 5;
    const COMPLETED = 6;
    const CANCELLED = 7;
    const SCHEDULED_MAIL = 8;
    const VOUCHERED = 9;

    public static $constants = [
        'added' => self::ADDED,
        'updated' => self::UPDATED,
        'quoted' => self::QUOTED,
        'followed_up' => self::FOLLOWED_UP,
        'amended' => self::AMENDED,
        'completed' => self::COMPLETED,
        'cancelled' => self::CANCELLED,
        'scheduled_mail' => self::SCHEDULED_MAIL
    ];

    public static $titles = [
        self::ADDED => 'Added',
        self::UPDATED => 'Updated',
        self::QUOTED => 'Quoted',
        self::FOLLOWED_UP => 'Followed Up',
        self::AMENDED => 'Amended',
        self::COMPLETED => 'Completed',
        self::CANCELLED => 'Cancelled',
        self::SCHEDULED_MAIL => 'Scheduled Mail',
        self::VOUCHERED => 'Vouchered'
    ];

    public static $headers = [
        self::ADDED => 'Added',
        self::UPDATED => 'Updated',
        self::QUOTED => 'Quoted',
        self::FOLLOWED_UP => 'Followed Up',
        self::AMENDED => 'Amended',
        self::COMPLETED => 'Completed',
        self::CANCELLED => 'Cancelled',
        self::SCHEDULED_MAIL => 'Scheduled Mail',
        self::VOUCHERED => 'Vouchered'
    ];
}