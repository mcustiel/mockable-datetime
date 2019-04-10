<?php

namespace Mcustiel\Mockable;

class TimeStamp
{
    /** @var int */
    private $timestamp;

    /** @param int timestamp */
    public function __construct($timestamp)
    {
        $this->ensureIsNatural($timestamp);
        $this->timestamp = $timestamp;
    }

    /** @return int */
    public function asInt()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     *
     * @throws \InvalidArgumentException
     */
    private function ensureIsNatural($timestamp)
    {
        if (!\is_int($timestamp)) {
            throw new \InvalidArgumentException(
                sprintf('Expected timestamp to be an integer, got: %s', \gettype($timestamp))
            );
        }
        if ($timestamp < 0) {
            throw new \InvalidArgumentException(
                sprintf('Timestamp is expected to be >= 0. Got %s', var_export($timestamp, true))
            );
        }
    }
}
