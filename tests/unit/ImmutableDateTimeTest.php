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

namespace Mcustiel\Mockable\Tests;

use Mcustiel\Mockable\DateTime;
use PHPUnit\Framework\TestCase;

class ImmutableDateTimeTest extends TestCase
{
    const SLEEP_TIME_IN_SECONDS = 3;

    /** @test */
    public function shouldReturnAFixedTimeEveryTimeItIsCalled()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:01');
        DateTime::setFixed($expected);

        $this->assertSame($expected->getTimestamp(), DateTime::newImmutablePhpDateTime()->getTimestamp());
        sleep(self::SLEEP_TIME_IN_SECONDS);
        $this->assertSame($expected->getTimestamp(), DateTime::newImmutablePhpDateTime()->getTimestamp());
    }

    /** @test */
    public function shouldReturnAFixedTimeEveryTimeItIsCalledInDifferentTimezone()
    {
        $expected = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2000-01-01 00:00:01',
            new \DateTimeZone('America/New_York')
        );
        DateTime::setFixed($expected);

        $phpDateTime = DateTime::newImmutablePhpDateTime();

        $this->assertSame($expected->getTimestamp(), $phpDateTime->getTimestamp());
        sleep(self::SLEEP_TIME_IN_SECONDS);
        $this->assertSame($expected->getTimestamp(), $phpDateTime->getTimestamp());
    }

    /** @test */
    public function shouldReturnTimeBasedInAnOffsetEveryTimeIsCalled()
    {
        $expected = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:01');
        DateTime::setOffset($expected);

        $this->assertSame($expected->getTimestamp(), DateTime::newImmutablePhpDateTime()->getTimestamp());
        sleep(self::SLEEP_TIME_IN_SECONDS);
        $this->assertSame(
            $expected->getTimestamp() + self::SLEEP_TIME_IN_SECONDS,
            DateTime::newImmutablePhpDateTime()->getTimestamp()
        );
    }

    /** @test */
    public function shouldReturnTimeBasedInAnOffsetEveryTimeIsCalledInDifferentTimezone()
    {
        $expected = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2000-01-01 00:00:01',
            new \DateTimeZone('America/New_York')
        );
        DateTime::setOffset($expected);

        $this->assertSame($expected->getTimestamp(), DateTime::newImmutablePhpDateTime()->getTimestamp());
        sleep(self::SLEEP_TIME_IN_SECONDS);
        $this->assertSame(
            $expected->getTimestamp() + self::SLEEP_TIME_IN_SECONDS,
            DateTime::newImmutablePhpDateTime()->getTimestamp()
        );
    }

    /** @test */
    public function shouldReturnSystemTimeEveryTimeIsCalled()
    {
        DateTime::setSystem();

        $this->assertSame(
            (new \DateTime())->getTimestamp(),
            DateTime::newImmutablePhpDateTime()->getTimestamp()
        );
        sleep(self::SLEEP_TIME_IN_SECONDS);
        $this->assertSame(
            (new \DateTime())->getTimestamp(),
            DateTime::newImmutablePhpDateTime()->getTimestamp()
        );
    }
}
