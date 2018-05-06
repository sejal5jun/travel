<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 29-06-2016
 * Time: 17:45
 */
namespace backend\models\enums;

class CategoryTypes {
    const DOMESTIC_HOLIDAYS = 1;
    const INTERNATIONAL_HOLIDAYS = 2;
    const LUXURY_HOLIDAYS = 3;
    const HONEYMOONS_CORNER = 4;
    const WEEKEND_GATEWAYS = 5;

    public static $constants = [
        'domestic_holidays' => self::DOMESTIC_HOLIDAYS,
        'international_holidays' => self::INTERNATIONAL_HOLIDAYS,
        'luxury_holidays' => self::LUXURY_HOLIDAYS,
        'honeymoons_corner' => self::HONEYMOONS_CORNER,
        'weekend_gateways' => self::WEEKEND_GATEWAYS
    ];

    public static $titles = [
        self::DOMESTIC_HOLIDAYS => 'Domestic Holidays',
        self::INTERNATIONAL_HOLIDAYS => 'International Holidays',
        self::LUXURY_HOLIDAYS => 'Luxury Holidays',
        self::HONEYMOONS_CORNER => 'Honeymoons corner',
        self::WEEKEND_GATEWAYS => 'Weekend Getaways'
    ];

    public static $headers = [
        self::DOMESTIC_HOLIDAYS => 'Domestic Holidays',
        self::INTERNATIONAL_HOLIDAYS => 'International Holidays',
        self::LUXURY_HOLIDAYS => 'Luxury Holidays',
        self::HONEYMOONS_CORNER => 'Honeymoons corner',
        self::WEEKEND_GATEWAYS => 'Weekend Getaways'
    ];
}