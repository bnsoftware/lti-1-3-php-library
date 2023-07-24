<?php

namespace BNSoftware\Lti1p3;

use Firebase\JWT\JWT;
use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;
use Throwable;

class LtiDeepLink
{
    private ILtiRegistration $registration;
    private string $deployment_id;
    private array $deepLinkSettings;

    /**
     * @param ILtiRegistration $registration
     * @param string           $deployment_id
     * @param array            $deepLinkSettings
     */
    public function __construct(ILtiRegistration $registration, string $deployment_id, array $deepLinkSettings)
    {
        $this->registration = $registration;
        $this->deployment_id = $deployment_id;
        $this->deepLinkSettings = $deepLinkSettings;
    }

    /**
     * @param ILtiRegistration $registration
     * @param string           $deployment_id
     * @param array            $deepLinkSettings
     * @return LtiDeepLink
     */
    public static function new(
        ILtiRegistration $registration,
        string $deployment_id,
        array $deepLinkSettings
    ): LtiDeepLink {
        return new LtiDeepLink($registration, $deployment_id, $deepLinkSettings);
    }

    /**
     * @param $resources
     * @return string
     * @throws Throwable
     */
    public function getResponseJwt($resources): string
    {
        $message_jwt = [
            'iss'                          => $this->registration->getClientId(),
            'aud'                          => [$this->registration->getIssuer()],
            'exp'                          => time() + 600,
            'iat'                          => time(),
            'nonce'                        => LtiOidcLogin::secureRandomString('nonce-'),
            LtiConstants::DEPLOYMENT_ID    => $this->deployment_id,
            LtiConstants::MESSAGE_TYPE     => LtiConstants::MESSAGE_TYPE_DEEP_LINK_RESPONSE,
            LtiConstants::VERSION          => LtiConstants::V1_3,
            LtiConstants::DL_CONTENT_ITEMS => array_map(
                function ($resource) {
                    return $resource->toArray();
                },
                $resources
            ),
        ];

        // https://www.imsglobal.org/spec/lti-dl/v2p0/#deep-linking-request-message
        // 'data' is an optional property which, if it exists, must be returned by the tool
        if (isset($this->deepLinkSettings['data'])) {
            $message_jwt[LtiConstants::DL_DATA] = $this->deepLinkSettings['data'];
        }

        return JWT::encode(
            $message_jwt,
            $this->registration->getToolPrivateKey(),
            'RS256',
            $this->registration->getKid()
        );
    }

    /**
     * This method builds an auto-submitting HTML form to post the deep linking response message
     * back to platform, as per LTI-DL 2.0 specification. The resulting HTML is then written to standard output,
     * so calling this method will automatically send an HTTP response to conclude the content selection flow.
     *
     * @param LtiDeepLinkResource[] $resources The list of selected resources to be sent to the platform
     * @throws Throwable
     * @todo Consider wrapping the content inside a well-formed HTML document,
     *       and returning it instead of directly writing to standard output
     */
    public function outputResponseForm(array $resources): void
    {
        $jwt = $this->getResponseJwt($resources);
        $formActionUrl = $this->deepLinkSettings['deep_link_return_url'];

        echo <<<HTML
<form id="auto_submit" action="$formActionUrl" method="POST">
    <input type="hidden" name="JWT" value="$jwt" />
    <input type="submit" name="Go" />
</form>
<script>document.getElementById('auto_submit').submit();</script>
HTML;
    }
}
