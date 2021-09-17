<?php

namespace Tests;

use Mockery;
use Packback\Lti1p3\Interfaces\ILtiRegistration;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\LtiNamesRolesProvisioningService;
use PHPUnit\Framework\TestCase;

class LtiNamesRolesProvisioningServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->connector = Mockery::mock(ILtiServiceConnector::class);
        $this->registration = Mockery::mock(ILtiRegistration::class);
    }

    public function testItInstantiates()
    {
        $nrps = new LtiNamesRolesProvisioningService($this->connector, $this->registration, []);

        $this->assertInstanceOf(LtiNamesRolesProvisioningService::class, $nrps);
    }

    public function testItGetsMembers()
    {
        $expected = ['members'];

        $nrps = new LtiNamesRolesProvisioningService($this->connector, $this->registration, [
            'context_memberships_url' => 'url',
        ]);
        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn([
                'headers' => [],
                'body' => ['members' => $expected],
            ]);

        $result = $nrps->getMembers();

        $this->assertEquals($expected, $result);
    }

    public function testItGetsMembersIteratively()
    {
        $response = ['members'];
        $expected = array_merge($response, $response);

        $nrps = new LtiNamesRolesProvisioningService($this->connector, $this->registration, [
            'context_memberships_url' => 'url',
        ]);
        // First response
        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn([
                'headers' => ['Link:Something<else>;rel="next"'],
                'body' => ['members' => $response],
            ]);
        // Second response
        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn([
                'headers' => [],
                'body' => ['members' => $response],
            ]);

        $result = $nrps->getMembers();

        $this->assertEquals($expected, $result);
    }
}
