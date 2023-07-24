<?php

namespace BNSoftware\Lti1p3;

use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;

class LtiRegistration implements ILtiRegistration
{
    private ?string $issuer;
    private ?string $clientId;
    private ?string $keySetUrl;
    private ?string $authTokenUrl;
    private ?string $authLoginUrl;
    private ?string $authServer;
    private ?string $toolPrivateKey;
    private ?string $kid;

    /**
     * @param array $registration
     */
    public function __construct(array $registration = [])
    {
        $this->issuer = $registration['issuer'] ?? null;
        $this->clientId = $registration['clientId'] ?? null;
        $this->keySetUrl = $registration['keySetUrl'] ?? null;
        $this->authTokenUrl = $registration['authTokenUrl'] ?? null;
        $this->authLoginUrl = $registration['authLoginUrl'] ?? null;
        $this->authServer = $registration['authServer'] ?? null;
        $this->toolPrivateKey = $registration['toolPrivateKey'] ?? null;
        $this->kid = $registration['kid'] ?? null;
    }

    /**
     * @param array $registration
     * @return LtiRegistration
     */
    public static function new(array $registration = []): LtiRegistration
    {
        return new LtiRegistration($registration);
    }

    /**
     * @param ?string $issuer
     * @return $this
     */
    public function setIssuer(?string $issuer): LtiRegistration
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @param ?string $clientId
     * @return $this
     */
    public function setClientId(?string $clientId): LtiRegistration
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @param ?string $keySetUrl
     * @return $this
     */
    public function setKeySetUrl(?string $keySetUrl): LtiRegistration
    {
        $this->keySetUrl = $keySetUrl;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getKeySetUrl(): ?string
    {
        return $this->keySetUrl;
    }

    /**
     * @param ?string $authTokenUrl
     * @return $this
     */
    public function setAuthTokenUrl(?string $authTokenUrl): LtiRegistration
    {
        $this->authTokenUrl = $authTokenUrl;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getAuthTokenUrl(): ?string
    {
        return $this->authTokenUrl;
    }

    /**
     * @param ?string $authLoginUrl
     * @return $this
     */
    public function setAuthLoginUrl(?string $authLoginUrl): LtiRegistration
    {
        $this->authLoginUrl = $authLoginUrl;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getAuthLoginUrl(): ?string
    {
        return $this->authLoginUrl;
    }

    /**
     * @param ?string $authServer
     * @return $this
     */
    public function setAuthServer(?string $authServer): LtiRegistration
    {
        $this->authServer = $authServer;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getAuthServer(): ?string
    {
        return empty($this->authServer) ? $this->authTokenUrl : $this->authServer;
    }

    /**
     * @param ?string $toolPrivateKey
     * @return $this
     */
    public function setToolPrivateKey(?string $toolPrivateKey): LtiRegistration
    {
        $this->toolPrivateKey = $toolPrivateKey;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getToolPrivateKey(): ?string
    {
        return $this->toolPrivateKey;
    }

    /**
     * @param ?string $kid
     * @return $this
     */
    public function setKid(?string $kid): LtiRegistration
    {
        $this->kid = $kid;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getKid(): ?string
    {
        return $this->kid ?? hash('sha256', trim($this->issuer . $this->clientId));
    }
}
