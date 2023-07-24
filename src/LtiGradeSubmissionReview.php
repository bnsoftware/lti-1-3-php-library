<?php

namespace BNSoftware\Lti1p3;

class LtiGradeSubmissionReview
{
    private ?string $reviewableStatus;
    private ?string $label;
    private ?string $url;
    private $custom;

    /**
     * @param ?array $gradeSubmission
     */
    public function __construct(?array $gradeSubmission = null)
    {
        $this->reviewableStatus = $gradeSubmission['reviewableStatus'] ?? null;
        $this->label = $gradeSubmission['label'] ?? null;
        $this->url = $gradeSubmission['url'] ?? null;
        $this->custom = $gradeSubmission['custom'] ?? null;
    }

    /**
     * @param ?array $gradeSubmission
     * @return LtiGradeSubmissionReview
     */
    public static function new(?array $gradeSubmission = null): LtiGradeSubmissionReview
    {
        return new LtiGradeSubmissionReview($gradeSubmission);
    }

    /**
     * @param ?string $status
     * @return $this
     */
    public function setReviewableStatus(?string $status): LtiGradeSubmissionReview
    {
        $this->reviewableStatus = $status;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getReviewableStatus(): ?string
    {
        return $this->reviewableStatus;
    }

    /**
     * @param ?string $label
     * @return $this
     */
    public function setLabel(?string $label): LtiGradeSubmissionReview
    {
        $this->label = $label;

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
     * @param ?string $url
     * @return $this
     */
    public function setUrl(?string $url): LtiGradeSubmissionReview
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setCustom($value): LtiGradeSubmissionReview
    {
        $this->custom = $value;

        return $this;
    }

    /**
     * @return ?mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return array_filter(
            [
                'reviewableStatus' => $this->reviewableStatus,
                'label'            => $this->label,
                'url'              => $this->url,
                'custom'           => $this->custom,
            ],
            '\BNSoftware\Lti1p3\Helpers\Helpers::checkIfNullValue'
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        // Additionally, includes the call back to filter out only NULL values
        return (string)json_encode($this->toArray());
    }
}
