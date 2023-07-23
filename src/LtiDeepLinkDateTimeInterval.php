<?php

namespace BNSoftware\Lti1p3;

use DateTime;
use DateTimeInterface;

class LtiDeepLinkDateTimeInterval
{
    private ?DateTime $start;
    private ?DateTime $end;

    /**
     * @param ?DateTime $start
     * @param ?DateTime $end
     * @throws LtiException
     */
    public function __construct(?DateTime $start = null, ?DateTime $end = null)
    {
        if ($start !== null && $end !== null && $end < $start) {
            throw new LtiException('Interval start time cannot be greater than end time');
        }

        $this->start = $start ?? null;
        $this->end = $end ?? null;
    }


    /**
     * @param ?DateTime $start
     * @param ?DateTime $end
     * @return LtiDeepLinkDateTimeInterval
     * @throws LtiException
     */
    public static function new(?DateTime $start = null, ?DateTime $end = null): LtiDeepLinkDateTimeInterval
    {
        return new LtiDeepLinkDateTimeInterval($start, $end);
    }

    /**
     * @param ?DateTime $start
     * @return $this
     */
    public function setStart(?DateTime $start): LtiDeepLinkDateTimeInterval
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    /**
     * @param DateTime|null $end
     * @return $this
     */
    public function setEnd(?DateTime $end): LtiDeepLinkDateTimeInterval
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return ?DateTime
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * @throws LtiException
     */
    public function toArray(): array
    {
        if (!isset($this->start) && !isset($this->end)) {
            throw new LtiException('At least one of the interval bounds must be specified on the object instance');
        }

        if ($this->start !== null && $this->end !== null && $this->end < $this->start) {
            throw new LtiException('Interval start time cannot be greater than end time');
        }

        $dateTimeInterval = [];

        if (isset($this->start)) {
            $dateTimeInterval['startDateTime'] = $this->start->format(DateTimeInterface::ATOM);
        }
        if (isset($this->end)) {
            $dateTimeInterval['endDateTime'] = $this->end->format(DateTimeInterface::ATOM);
        }

        return $dateTimeInterval;
    }
}
