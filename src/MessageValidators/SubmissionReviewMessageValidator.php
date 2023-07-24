<?php

namespace BNSoftware\Lti1p3\MessageValidators;

use BNSoftware\Lti1p3\Interfaces\IMessageValidator;
use BNSoftware\Lti1p3\LtiConstants;
use BNSoftware\Lti1p3\LtiException;

class SubmissionReviewMessageValidator extends AbstractMessageValidator
{
    /**
     * @return string
     */
    public static function getMessageType(): string
    {
        return LtiConstants::MESSAGE_TYPE_SUBMISSION_REVIEW;
    }

    /**
     * @param array $jwtBody
     * @return void
     * @throws LtiException
     */
    public static function validate(array $jwtBody): void
    {
        static::validateGenericMessage($jwtBody);

        if (empty($jwtBody[LtiConstants::RESOURCE_LINK]['id'])) {
            throw new LtiException('Missing Resource Link Id');
        }
        if (empty($jwtBody[LtiConstants::FOR_USER])) {
            throw new LtiException('Missing For User');
        }
    }
}
