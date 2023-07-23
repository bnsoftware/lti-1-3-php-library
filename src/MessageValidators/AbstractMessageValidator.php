<?php

namespace BNSoftware\Lti1p3\MessageValidators;

use BNSoftware\Lti1p3\Interfaces\IMessageValidator;
use BNSoftware\Lti1p3\LtiConstants;
use BNSoftware\Lti1p3\LtiException;

abstract class AbstractMessageValidator implements IMessageValidator
{
    /**
     * @return string
     */
    abstract public static function getMessageType(): string;

    /**
     * @param array $jwtBody
     * @return bool
     */
    public static function canValidate(array $jwtBody): bool
    {
        return $jwtBody[LtiConstants::MESSAGE_TYPE] === static::getMessageType();
    }

    /**
     * @param array $jwtBody
     */
    abstract public static function validate(array $jwtBody): void;

    /**
     * @param array $jwtBody
     * @return void
     * @throws LtiException
     */
    public static function validateGenericMessage(array $jwtBody): void
    {
        if (empty($jwtBody['sub'])) {
            throw new LtiException('Must have a user (sub)');
        }
        if (!isset($jwtBody[LtiConstants::VERSION])) {
            throw new LtiException('Missing LTI Version');
        }
        if ($jwtBody[LtiConstants::VERSION] !== LtiConstants::V1_3) {
            throw new LtiException('Incorrect version, expected 1.3.0');
        }
        if (!isset($jwtBody[LtiConstants::ROLES])) {
            throw new LtiException('Missing Roles Claim');
        }
    }
}
