<?php

namespace BNSoftware\Lti1p3\MessageValidators;

use BNSoftware\Lti1p3\Interfaces\IMessageValidator;
use BNSoftware\Lti1p3\LtiConstants;
use BNSoftware\Lti1p3\LtiException;

class DeepLinkMessageValidator extends AbstractMessageValidator
{
    /**
     * @return string
     */
    public static function getMessageType(): string
    {
        return LtiConstants::MESSAGE_TYPE_DEEP_LINK;
    }

    /**
     * @param array $jwtBody
     * @return void
     * @throws LtiException
     */
    public static function validate(array $jwtBody): void
    {
        static::validateGenericMessage($jwtBody);

        if (empty($jwtBody[LtiConstants::DL_DEEP_LINK_SETTINGS])) {
            throw new LtiException('Missing Deep Linking Settings');
        }
        $deep_link_settings = $jwtBody[LtiConstants::DL_DEEP_LINK_SETTINGS];
        if (empty($deep_link_settings['deep_link_return_url'])) {
            throw new LtiException('Missing Deep Linking Return URL');
        }
        if (
            empty($deep_link_settings['accept_types'])
            || !in_array('ltiResourceLink', $deep_link_settings['accept_types'])
        ) {
            throw new LtiException('Must support resource link placement types');
        }
        if (empty($deep_link_settings['accept_presentation_document_targets'])) {
            throw new LtiException('Must support a presentation type');
        }
    }
}
