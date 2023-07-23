<?php

namespace BNSoftware\Lti1p3;

use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;
use BNSoftware\Lti1p3\Interfaces\ILtiServiceConnector;
use BNSoftware\Lti1p3\Interfaces\IServiceRequest;

abstract class LtiAbstractService
{
    private ILtiServiceConnector $serviceConnector;
    private ILtiRegistration $registration;
    private array $serviceData;

    /**
     * @param ILtiServiceConnector $serviceConnector
     * @param ILtiRegistration     $registration
     * @param array                $serviceData
     */
    public function __construct(
        ILtiServiceConnector $serviceConnector,
        ILtiRegistration $registration,
        array $serviceData
    ) {
        $this->serviceConnector = $serviceConnector;
        $this->registration = $registration;
        $this->serviceData = $serviceData;
    }

    /**
     * @return array
     */
    public function getServiceData(): array
    {
        return $this->serviceData;
    }

    /**
     * @param array $serviceData
     * @return $this
     */
    public function setServiceData(array $serviceData): self
    {
        $this->serviceData = $serviceData;

        return $this;
    }

    /**
     * @return array
     */
    abstract public function getScope(): array;

    /**
     * @param array $scopes
     * @return void
     * @throws LtiException
     */
    protected function validateScopes(array $scopes): void
    {
        if (empty(array_intersect($scopes, $this->getScope()))) {
            throw new LtiException('Missing required scope', 1);
        }
    }

    /**
     * @param IServiceRequest $request
     * @return array
     */
    protected function makeServiceRequest(IServiceRequest $request): array
    {
        return $this->serviceConnector->makeServiceRequest(
            $this->registration,
            $this->getScope(),
            $request,
        );
    }

    /**
     * @param IServiceRequest $request
     * @param ?string         $key
     * @return array
     */
    protected function getAll(IServiceRequest $request, ?string $key = null): array
    {
        return $this->serviceConnector->getAll(
            $this->registration,
            $this->getScope(),
            $request,
            $key
        );
    }
}
