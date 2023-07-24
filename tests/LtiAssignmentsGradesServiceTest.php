<?php

namespace Tests;

use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;
use BNSoftware\Lti1p3\Interfaces\ILtiServiceConnector;
use BNSoftware\Lti1p3\LtiAssignmentsGradesService;
use BNSoftware\Lti1p3\LtiConstants;
use BNSoftware\Lti1p3\LtiException;
use BNSoftware\Lti1p3\LtiLineItem;
use DateTime;
use Mockery;
use Throwable;

class LtiAssignmentsGradesServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->connector = Mockery::mock(ILtiServiceConnector::class);
        $this->registration = Mockery::mock(ILtiRegistration::class);
    }

    public function testItInstantiates()
    {
        $service = new LtiAssignmentsGradesService($this->connector, $this->registration, []);

        $this->assertInstanceOf(LtiAssignmentsGradesService::class, $service);
    }

    public function testItGetsSingleLineItem()
    {
        $ltiLineItemData = [
            'id'            => 'testId',
            'startDateTime' => new DateTime('2020-01-01T00:00:00Z'),
            'endDateTime'   => new DateTime('2020-01-05T00:00:00Z'),
        ];

        $serviceData = [
            'scope' => [LtiConstants::AGS_SCOPE_LINE_ITEM],
        ];

        $service = new LtiAssignmentsGradesService($this->connector, $this->registration, $serviceData);

        $response = [
            'body' => $ltiLineItemData,
        ];

        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn($response);

        $expected = new LtiLineItem($ltiLineItemData);

        $result = $service->getLineItem('someUrl');

        $this->assertEquals($expected, $result);
    }

    public function testItGetsSingleLineItemWithReadonlyScope()
    {
        $ltiLineItemData = [
            'id' => 'testId',
            'startDateTime' => new DateTime('2020-01-01T00:00:00Z'),
            'endDateTime'   => new DateTime('2020-01-05T00:00:00Z'),
        ];

        $serviceData = [
            'scope' => [LtiConstants::AGS_SCOPE_LINE_ITEM_READONLY],
        ];

        $service = new LtiAssignmentsGradesService($this->connector, $this->registration, $serviceData);

        $response = [
            'body' => $ltiLineItemData,
        ];

        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn($response);

        $expected = new LtiLineItem($ltiLineItemData);

        $result = $service->getLineItem('someUrl');

        $this->assertEquals($expected, $result);
    }

    public function testItDeletesALineItem()
    {
        $serviceData = [
            'scope'    => [LtiConstants::AGS_SCOPE_LINE_ITEM],
            'lineitem' => 'https://canvas.localhost/api/lti/courses/8/line_items/27',
        ];

        $service = new LtiAssignmentsGradesService($this->connector, $this->registration, $serviceData);

        $response = [
            'status' => 204,
            'body'   => null,
        ];

        $this->connector->shouldReceive('makeServiceRequest')
            ->once()->andReturn($response);

        $result = $service->deleteLineItem();

        $this->assertEquals($response, $result);
    }
}
