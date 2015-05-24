<?php
namespace Mcustiel\Mockable;

class DateTime
{
    private $type;
    private $timestamp;

    private $constructTime;

    public function __construct()
    {
        $this->constructTime = (new \DateTime())->getTimestamp();
        $this->type = DateTimeUtils::getType();
        $this->timestamp = DateTimeUtils::getTimestamp();
    }

    public function toPhpDateTime()
    {
        if ($this->type === DateTimeUtils::DATETIME_SYSTEM) {
            return new \DateTime("now", new \DateTimeZone('UTC'));
        } elseif ($this->type === DateTimeUtils::DATETIME_FIXED) {
            return $this->createPhpDateWithTimestamp($this->timestamp);
        }

        return $this->createPhpDateWithTimestamp(
            $this->timestamp + abs(
                (new \DateTime('now', new \DateTimeZone('UTC')))->getTimestamp() - $this->constructTime
            )
        );
    }

    private function createPhpDateWithTimestamp($timestamp)
    {
        return new \DateTime("@$timestamp", new \DateTimeZone('UTC'));
    }
}
