<?php
/**
 * PER_PERSON by PhpStorm.
 * User: Priyanka
 * Date: 29-06-2016
 * Time: 17:51
 */

namespace backend\models\enums;

class PricingTypes {
    const PER_PERSON_ON_TWIN_SHARING = 1;
    const PER_ADULT = 2;
    const PER_CHILD = 3;
    
    public static $constants = [
        'per_person_on_twin_sharing' => self::PER_PERSON_ON_TWIN_SHARING,
        'per_adult' => self::PER_ADULT,
        'per_child' => self::PER_CHILD
    ];

    public static $titles = [
        self::PER_PERSON_ON_TWIN_SHARING => 'Per Person On Twin Sharing',
        self::PER_ADULT => 'Per Adult',
        self::PER_CHILD => 'Per Child'
    ];

    public static $headers = [
        self::PER_PERSON_ON_TWIN_SHARING => 'Per Person On Twin Sharing',
        self::PER_ADULT => 'Per Adult',
        self::PER_CHILD => 'Per Child'
    ];
}