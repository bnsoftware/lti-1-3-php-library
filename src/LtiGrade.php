<?php

namespace BNSoftware\Lti1p3;

use DateTime;
use Throwable;

class LtiGrade
{
    private ?float $scoreGiven;
    private ?float $scoreMaximum;
    private ?string $comment;
    private ?string $activityProgress;
    private ?string $gradingProgress;
    private ?LtiTimestamp $timestamp;
    private ?string $userId;
    private ?string $submissionReview;
    private ?array $canvasExtension;

    /**
     * @param ?array $grade
     * @throws Throwable
     */
    public function __construct(?array $grade = null)
    {
        $this->scoreGiven = $grade['scoreGiven'] ?? null;
        $this->scoreMaximum = $grade['scoreMaximum'] ?? null;
        $this->comment = $grade['comment'] ?? null;
        $this->activityProgress = $grade['activityProgress'] ?? null;
        $this->gradingProgress = $grade['gradingProgress'] ?? null;
        $this->timestamp = empty($grade['timestamp'] ?? null) ? null : LtiTimestamp::new($grade['timestamp']);
        $this->userId = $grade['userId'] ?? null;
        $this->submissionReview = $grade['submissionReview'] ?? null;
        $this->canvasExtension = $grade['https://canvas.instructure.com/lti/submission'] ?? null;
    }


    /**
     * @param ?array $grade
     * @return LtiGrade
     * @throws Throwable
     */
    public static function new(?array $grade = null): LtiGrade
    {
        return new LtiGrade($grade);
    }

    /**
     * @param ?float $value
     * @return $this
     */
    public function setScoreGiven(?float $value): LtiGrade
    {
        $this->scoreGiven = $value;

        return $this;
    }

    /**
     * @return ?float
     */
    public function getScoreGiven(): ?float
    {
        return $this->scoreGiven;
    }

    /**
     * @param ?float $value
     * @return $this
     */
    public function setScoreMaximum(?float $value): LtiGrade
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
     * @param ?string $comment
     * @return $this
     */
    public function setComment(?string $comment): LtiGrade
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setActivityProgress(?string $value): LtiGrade
    {
        $this->activityProgress = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getActivityProgress(): ?string
    {
        return $this->activityProgress;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setGradingProgress(?string $value): LtiGrade
    {
        $this->gradingProgress = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getGradingProgress(): ?string
    {
        return $this->gradingProgress;
    }

    public function setTimestamp(?LtiTimestamp $value): LtiGrade
    {
        $this->timestamp = $value;

        return $this;
    }

    /**
     * @return ?LtiTimestamp
     */
    public function getTimestamp(): ?LtiTimestamp
    {
        return $this->timestamp;
    }

    public function setUserId(?string $value): LtiGrade
    {
        $this->userId = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param ?string $value
     * @return $this
     */
    public function setSubmissionReview(?string $value): LtiGrade
    {
        $this->submissionReview = $value;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getSubmissionReview(): ?string
    {
        return $this->submissionReview;
    }

    /**
     * Custom Extension for Canvas.
     * https://documentation.instructure.com/doc/api/score.html
     *
     * @param ?array $value
     * @return $this
     */
    public function setCanvasExtension(?array $value): LtiGrade
    {
        $this->canvasExtension = $value;

        return $this;
    }

    /**
     * Custom Extension for Canvas.
     * https://documentation.instructure.com/doc/api/score.html
     *
     * @return ?array
     */
    public function getCanvasExtension(): ?array
    {
        return $this->canvasExtension;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // Additionally, includes the call back to filter out only NULL values
        return array_filter(
            [
                'scoreGiven'                                    => $this->scoreGiven,
                'scoreMaximum'                                  => $this->scoreMaximum,
                'comment'                                       => $this->comment,
                'activityProgress'                              => $this->activityProgress,
                'gradingProgress'                               => $this->gradingProgress,
                'timestamp'                                     => !is_null($this->timestamp)
                    ? $this->timestamp->format()
                    : null,
                'userId'                                        => $this->userId,
                'submissionReview'                              => $this->submissionReview,
                'https://canvas.instructure.com/lti/submission' => $this->canvasExtension,
            ],
            '\BNSoftware\Lti1p3\Helpers\Helpers::checkIfNullValue'
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)json_encode($this->toArray());
    }
}
