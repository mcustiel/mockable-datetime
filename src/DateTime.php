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

class DateTime
{
    /** @var int */
    private $type;
    /** @var int */
    private $timestamp;
    /** @var string */
    private $constructTime;
    /** @var \DateTimeZone|null */
    private $constructTimeZone;
    /** @var int */
    private $timeStampAtInstatiation;

    /**
     * @param string             $time
     * @param \DateTimeZone|null $timeZone
     *
     * @throws \Exception
     */
    public function __construct($time = 'now', \DateTimeZone $timeZone = null)
    {
        $this->timeStampAtInstatiation = (new \DateTime())->getTimestamp();
        $this->constructTime = $time;
        $this->constructTimeZone = $timeZone;
        $this->type = DateTimeUtils::getType();
        $this->timestamp = DateTimeUtils::getTimestamp();
    }

    /**
     * @throws \Exception
     *
     * @return DateTime
     */
    public function toPhpDateTime()
    {
        if (DateTimeUtils::DATETIME_SYSTEM === $this->type) {
            return new \DateTime($this->constructTime, $this->constructTimeZone);
        }

        if (DateTimeUtils::DATETIME_FIXED === $this->type) {
            return $this->getFixedTimeFromConfiguredTimestamp();
        }

        $date = $this->getFixedTimeFromConfiguredTimestamp();
        $timePassedSinceInstantiation = abs((new \DateTime('now'))->getTimestamp() - $this->timeStampAtInstatiation);
        $date->modify("+{$timePassedSinceInstantiation} seconds");

        return $date;
    }

    /**
     * @throws \Exception
     *
     * @return DateTime
     */
    private function getFixedTimeFromConfiguredTimestamp()
    {
        $timeStamp = DateTimeUtils::getTimestamp();
        $date = new \DateTime("@{$timeStamp}");
        if ('now' !== $this->constructTime) {
            $date->modify($this->constructTime);
        }
        if (null !== $this->constructTimeZone) {
            $date->setTimezone($this->constructTimeZone);
        }

        return $date;
    }
}
