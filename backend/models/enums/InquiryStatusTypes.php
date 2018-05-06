<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 24-06-2016
 * Time: 17:45
 */

namespace backend\models\enums;

class InquiryStatusTypes {
    const IN_QUOTATION = 1;
    const QUOTED = 2;
    const AMENDED = 3;
    const COMPLETED = 4;
    const CANCELLED = 5;
    const ARCHIVED = 6;
    const VOUCHERED = 7;

    public static $constants = [
        'in_quotation' => self::IN_QUOTATION,
        'quoted' => self::QUOTED,
        'amended' => self::AMENDED,
        'completed' => self::COMPLETED,
        'cancelled' => self::CANCELLED
    ];

    public static $titles = [
        self::QUOTED => 'Next Follow Up',
        self::AMENDED => 'Amended',
        self::COMPLETED => 'Booked',
        self::CANCELLED => 'Cancelled'
    ];

    public static $can_status = [

        self::AMENDED => 'Amended',
        self::CANCELLED => 'Cancelled',
        self::VOUCHERED => 'Vouchered',


    ];


    public static $headers = [
        self::IN_QUOTATION => 'In Quotation',
        self::QUOTED => 'Followups',
        self::AMENDED => 'Amended',
        self::COMPLETED => 'Bookings',
        self::CANCELLED => 'Cancelled',
         self::VOUCHERED => 'Vouchered'
    ];

    public static $index_status = [
        self::IN_QUOTATION => 'In Quotation',
        self::QUOTED => 'In Followup',
        self::AMENDED => 'Amended',
        self::COMPLETED => 'Booked',
        self::CANCELLED => 'Cancelled',
        self::VOUCHERED => 'Vouchered'
    ];

    public static $status = [
        self::IN_QUOTATION => 'Pending Inquiries',
        self::QUOTED => 'Followups',
        self::COMPLETED => 'Bookings',
        self::CANCELLED => 'Cancelled Inquiries',
        self::VOUCHERED => 'Vouchered Inquiries'
    ];
}