<?php
namespace Mcustiel\Mockable;

class DateTimeUtils
{
    const DATETIME_SYSTEM = 0;
    const DATETIME_FIXED = 1;
    const DATETIME_OFFSET = 2;

    private static $type = self::DATETIME_SYSTEM;
    private static $timestamp = 0;

    public static function setCurrentTimestampFixed($timestamp)
    {
        self::$type = self::DATETIME_FIXED;
        self::$timestamp = $timestamp;
    }

    public static function setCurrentTimestampSystem()
    {
        self::$type = self::DATETIME_SYSTEM;
        self::$timestamp = 0;
    }

    public static function setCurrentTimestampOffset($timestamp)
    {
        self::$type = self::DATETIME_OFFSET;
        self::$timestamp = $timestamp;
    }

    public static function getType()
    {
        return self::$type;
    }

    public static function getTimestamp()
    {
        return self::$timestamp;
    }
}