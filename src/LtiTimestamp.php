<?php

namespace BNSoftware\Lti1p3;

use DateTime;
use InvalidArgumentException;
use Throwable;

class LtiTimestamp
{
    public const TIMESTAMP_FORMAT_ISO_8601 = "Y-m-d\\TH:i:s.v\Z";

    private DateTime $timestamp;

    /**
     * @param DateTime|LtiTimestamp|string $timestamp
     * @throws InvalidArgumentException|Throwable
     */
    public function __construct($timestamp = null)
    {
        if (is_null($timestamp)) {
            $this->timestamp = new DateTime();
        } elseif ($timestamp instanceof LtiTimestamp) {
            $this->timestamp = $timestamp->getTimestamp();
        } elseif ($timestamp instanceof DateTime) {
            $this->timestamp = $timestamp;
        } elseif (is_string($timestamp)) {
            $this->timestamp = new DateTime($timestamp);
        } else {
            throw new InvalidArgumentException();
        }

    }

    /**
     * @param DateTime|LtiTimestamp|string $timestamp
     * @return LtiTimestamp|null
     * @throws InvalidArgumentException|Throwable
     */
    public static function new($timestamp = null): ?LtiTimestamp
    {
        return new LtiTimestamp($timestamp);
    }

    /**
     * @param DateTime $timestamp
     * @return $this
     */
    public function setTimestamp(DateTime $timestamp): LtiTimestamp
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function __toString(): string
    {
        return $this->timestamp->format(self::TIMESTAMP_FORMAT_ISO_8601);
    }

    public function format(?string $format = self::TIMESTAMP_FORMAT_ISO_8601): string
    {
        return $this->timestamp->format($format);
    }
}
