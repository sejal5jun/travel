<?php
/**
 * Created by PhpStorm.
 * User: Priyanka
 * Date: 18-08-2016
 * Time: 12:40
 */
namespace backend\models\enums;

class CronTypes {
    const PENDING_INQUIRY_REMAINDER = 1;
    const DAILY_FOLLOWUP_REMAINDER = 2;
    const SCHEDULE_FOLLOWUP = 3;
    const PENDING_INQUIRY = 4;
    const YESTERDAYS_INQUIRY_REPORT = 5;
    const YESTERDAYS_PERFORMANCE_REPORT = 6;
    const PENDING_FOLLOWUP_REMINDER = 7;

    public static $constants = [
        'PENDING_INQUIRY_REMAINDER' => self::PENDING_INQUIRY_REMAINDER,
        'DAILY_FOLLOWUP_REMAINDER' => self::DAILY_FOLLOWUP_REMAINDER,
        'SCHEDULE_FOLLOWUP' => self::SCHEDULE_FOLLOWUP,
        'YESTERDAYS_INQUIRY_REPORT' => self::YESTERDAYS_INQUIRY_REPORT,
        'YESTERDAYS_PERFORMANCE_REPORT' => self::YESTERDAYS_PERFORMANCE_REPORT,
        'PENDING_FOLLOWUP_REMINDER' => self::PENDING_FOLLOWUP_REMINDER
    ];

    public static $titles = [
        self::PENDING_INQUIRY_REMAINDER => 'Pending Inquiry Reminder',
        self::DAILY_FOLLOWUP_REMAINDER => 'Daily Followup Reminder',
        self::SCHEDULE_FOLLOWUP => 'Schedule Followup',
        self::YESTERDAYS_INQUIRY_REPORT => "Yesterday's Inquiry Report",
        self::YESTERDAYS_PERFORMANCE_REPORT => "Yesterday's Performance Report",
        self::PENDING_FOLLOWUP_REMINDER => "Pending Followup Reminder"
    ];

    public static $headers = [
        self::PENDING_INQUIRY_REMAINDER => 'Pending Inquiry Reminder',
        self::DAILY_FOLLOWUP_REMAINDER => 'Daily Followup Reminder',
        self::SCHEDULE_FOLLOWUP => 'Schedule Followup',
        self::YESTERDAYS_INQUIRY_REPORT => "Yesterday's Inquiry Report",
        self::YESTERDAYS_PERFORMANCE_REPORT => "Yesterday's Performance Report",
        self::PENDING_FOLLOWUP_REMINDER => "Pending Followup Reminder"
    ];
}