<?php

namespace BNSoftware\Lti1p3;

use Throwable;

class LtiLineItem
{
    private ?string $id;
    private ?float $scoreMaximum;
    private ?string $label;
    private ?string $resourceId;
    private ?string $resourceLinkId;
    private ?string $tag;
    private ?LtiTimestamp $startDateTime;
    private ?LtiTimestamp $endDateTime;
    private ?bool $gradesReleased;

    public function __construct(?array $lineItem = null)
    {
        $this->id = $lineItem['id'] ?? null;
        $this->scoreMaximum = $lineItem['scoreMaximum'] ?? null;
        $this->label = $lineItem['label'] ?? null;
        $this->resourceId = $lineItem['resourceId'] ?? null;
        $this->resourceLinkId = $lineItem['resourceLinkId'] ?? null;
        $this->tag = $lineItem['tag'] ?? null;
        $this->startDateTime = empty($lineItem['startDateTime'])
            ? null
            : LtiTimestamp::new($lineItem['startDateTime']);
        $this->endDateTime = empty($lineItem['endDateTime'])
            ? null
            : LtiTimestamp::new($lineItem['endDateTime']);
        $this->gradesReleased = $lineItem['gradesReleased'] ?? null;
    }

    /**
     * @param ?array $lineItem
     * @return LtiLineItem
     * @throws Throwable
     */
    public static function new(?array $lineItem = null)
    {
        return new LtiLineItem($lineItem);
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setId(?string $value): LtiLineItem
    {
        $this->id = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setLabel(?string $value): LtiLineItem
    {
        $this->label = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param ?float $value
     * @return $this
     */
    public function setScoreMaximum(?float $value): LtiLineItem
    {
        $this->scoreMaximum = $value;

        return $this;
    }

    /**
     * @return ?float
     */
    public function getScoreMaximum(): ?float
    {
        return $this->scoreMaximum;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setResourceId(?string $value): LtiLineItem
    {
        $this->resourceId = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setResourceLinkId(?string $value): LtiLineItem
    {
        $this->resourceLinkId = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getResourceLinkId(): ?string
    {
        return $this->resourceLinkId;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setTag(?string $value): LtiLineItem
    {
        $this->tag = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @param ?LtiTimestamp $value
     * @return $this
     */
    public function setStartDateTime(?LtiTimestamp $value): LtiLineItem
    {
        $this->startDateTime = $value;

        return $this;
    }

    /**
     * @return ?LtiTimestamp
     */
    public function getStartDateTime(): ?LtiTimestamp
    {
        return $this->startDateTime;
    }

    /**
     * @param ?LtiTimestamp $value
     * @return $this
     */
    public function setEndDateTime(?LtiTimestamp $value): LtiLineItem
    {
        $this->endDateTime = $value;

        return $this;
    }

    /**
     * @return ?LtiTimestamp
     */
    public function getEndDateTime(): ?LtiTimestamp
    {
        return $this->endDateTime;
    }

    /**
     * @param ?bool $value
     * @return $this
     */
    public function setGradesReleased(?bool $value): LtiLineItem
    {
        $this->gradesReleased = $value;

        return $this;
    }

    /**
     * @return ?bool
     */
    public function getGradesReleased(): ?bool
    {
        return $this->gradesReleased;
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'id'             => $this->id,
                'scoreMaximum'   => $this->scoreMaximum,
                'label'          => $this->label,
                'resourceId'     => $this->resourceId,
                'resourceLinkId' => $this->resourceLinkId,
                'tag'            => $this->tag,
                'startDateTime'  => $this->startDateTime ? $this->startDateTime->format() : null,
                'endDateTime'    => $this->endDateTime ? $this->endDateTime->format() : null,
            ],
            '\BNSoftware\Lti1p3\Helpers\Helpers::checkIfNullValue'
        );
    }

    public function __toString(): string
    {
        return (string)json_encode($this->toArray());
    }
}
