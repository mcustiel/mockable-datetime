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

    /** @var int */
    private static $type = self::DATETIME_SYSTEM;
    /** @var TimeStamp|null */
    private static $timestamp;
    /** @var int */
    private static $offsetTimestamp = 0;

    /** @param int $timestamp */
    public static function setCurrentTimestampFixed($timestamp)
    {
        self::$type = self::DATETIME_FIXED;
        self::$timestamp = new TimeStamp($timestamp);
    }

    public static function setCurrentTimestampSystem()
    {
        self::$type = self::DATETIME_SYSTEM;
        self::$timestamp = new TimeStamp(0);
    }

    /** @param int $timestamp */
    public static function setCurrentTimestampOffset($timestamp)
    {
        self::$type = self::DATETIME_OFFSET;
        self::$timestamp = new TimeStamp($timestamp);
        self::$offsetTimestamp = (new \DateTime())->getTimestamp();
    }

    /**
     * @param string        $time
     * @param \DateTimeZone $timeZone
     *
     * @return \DateTime
     */
    public static function createPhpDateTime($time = 'now', \DateTimeZone $timeZone = null)
    {
        if (self::DATETIME_SYSTEM === self::$type) {
            return new \DateTime($time, $timeZone);
        }

        if (self::DATETIME_FIXED === self::$type) {
            return self::getFixedTimeFromConfiguredTimestamp($time, $timeZone);
        }

        $date = self::getFixedTimeFromConfiguredTimestamp($time, $timeZone);
        $timePassedSinceInstantiation = abs(
            (new \DateTime())->getTimestamp() - self::$offsetTimestamp
        );
        $date->modify("+{$timePassedSinceInstantiation} seconds");

        return $date;
    }

    /**
     * @param mixed      $time
     * @param null|mixed $timeZone
     *
     * @throws \Exception
     *
     * @return \DateTime
     */
    private static function getFixedTimeFromConfiguredTimestamp($time, $timeZone = null)
    {
        $timeStamp = null === self::$timestamp ? 0 : self::$timestamp->asInt();
        $date = new \DateTime("@{$timeStamp}");
        if ('now' !== $time) {
            $date->modify($time);
        }
        if (null !== $timeZone) {
            $date->setTimezone($timeZone);
        }

        return $date;
    }
}
