<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 15-07-2016
 * Time: 12:28
 */
namespace backend\models\enums;

class PackageTypes {



    const PACKAGE_WITH_ITINERARY = 1;
    const PACKAGE_WITHOUT_ITINERARY = 2;


    public static $constants = [


        'package_with_itinerary' => self::PACKAGE_WITH_ITINERARY,
        'package_without_itinerary' => self::PACKAGE_WITHOUT_ITINERARY,
    ];

    public static $titles = [


        self::PACKAGE_WITH_ITINERARY => 'Package With Itinerary',
        self::PACKAGE_WITHOUT_ITINERARY => 'Package Without Itinerary',
    ];

    public static $headers = [


        self::PACKAGE_WITH_ITINERARY => 'Package With Itinerary',
        self::PACKAGE_WITHOUT_ITINERARY => 'Package Without Itinerary',
    ];
}