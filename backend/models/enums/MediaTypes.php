<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 18-07-2016
 * Time: 16:53
 */
namespace backend\models\enums;

class MediaTypes {
    const PROFILE_PHOTO = 1;
    const PACKAGE_ITINERARY_PHOTO = 2;
    const INQUIRY_PACKAGE_ITINERARY_PHOTO = 3;
    const SUMMERNOTE_EDITOR_PHOTO = 3;

    public static $constants = [
        'added' => self::PROFILE_PHOTO,
        'updated' => self::PACKAGE_ITINERARY_PHOTO,
        'quoted' => self::INQUIRY_PACKAGE_ITINERARY_PHOTO,
        'summernote_editor_photo' => self::SUMMERNOTE_EDITOR_PHOTO
    ];

    public static $titles = [
        self::PROFILE_PHOTO => 'Profile Photo',
        self::PACKAGE_ITINERARY_PHOTO => 'Package Itinerary Photo',
        self::INQUIRY_PACKAGE_ITINERARY_PHOTO => 'Inquiry Package Itinerary Photo',
        self::SUMMERNOTE_EDITOR_PHOTO => 'Summernote Editor Photo'
    ];

    public static $headers = [
        self::PROFILE_PHOTO => 'Profile Photo',
        self::PACKAGE_ITINERARY_PHOTO => 'Package Itinerary Photo',
        self::INQUIRY_PACKAGE_ITINERARY_PHOTO => 'Inquiry Package Itinerary Photo',
        self::SUMMERNOTE_EDITOR_PHOTO => 'Summernote Editor Photo'
    ];
}