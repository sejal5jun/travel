<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 24-06-2016
 * Time: 19:21
 */
namespace backend\models\enums;

class InquiryTypes {
    const PACKAGE_WITH_ITINERARY = 1;
    const PACKAGE_WITHOUT_ITINERARY = 2;
    const PER_ROOM_PER_NIGHT = 3;

    public static $constants = [
        'package_with_itinerary' => self::PACKAGE_WITH_ITINERARY,
        'package_without_itinerary' => self::PACKAGE_WITHOUT_ITINERARY,
        'per_room_per_night' => self::PER_ROOM_PER_NIGHT,
    ];

    public static $titles = [
        self::PACKAGE_WITH_ITINERARY => 'Package With Itinerary',
        self::PACKAGE_WITHOUT_ITINERARY => 'Package Without Itinerary',
        self::PER_ROOM_PER_NIGHT => 'Per Room Per Night',
    ];

    public static $headers = [
        self::PACKAGE_WITH_ITINERARY => 'Package With Itinerary',
        self::PACKAGE_WITHOUT_ITINERARY => 'Package Without Itinerary',
        self::PER_ROOM_PER_NIGHT => 'Per Room Per Night',
    ];
}