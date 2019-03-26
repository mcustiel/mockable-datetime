<?php
/**
 * This file is part of mockable-datetime.
 *
 * mockable-datetime is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * mockable-datetime is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with mockable-datetime.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Mcustiel\Mockable;

class DateTimeUtils
{
    const DATETIME_SYSTEM = 0;
    const DATETIME_FIXED = 1;
    const DATETIME_OFFSET = 2;

    /** @var int  */
    private static $type = self::DATETIME_SYSTEM;
    /** @var int  */
    private static $timestamp = 0;

    /**
     * @param int $timestamp
     */
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

    /** @param int $timestamp */
    public static function setCurrentTimestampOffset($timestamp)
    {
        self::$type = self::DATETIME_OFFSET;
        self::$timestamp = $timestamp;
    }

    /** @return int */
    public static function getType()
    {
        return self::$type;
    }

    /** @return int */
    public static function getTimestamp()
    {
        return self::$timestamp;
    }
}
