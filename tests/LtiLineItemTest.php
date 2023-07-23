<?php

namespace Tests;

use BNSoftware\Lti1p3\LtiLineItem;
use BNSoftware\Lti1p3\LtiTimestamp;
use DateTime;

class LtiLineItemTest extends TestCase
{
    public function setUp(): void
    {
        $this->lineItem = new LtiLineItem();
    }

    public function testItInstantiates()
    {
        $this->assertInstanceOf(LtiLineItem::class, $this->lineItem);
    }

    public function testItCreatesANewInstance()
    {
        $grade = LtiLineItem::new();

        $this->assertInstanceOf(LtiLineItem::class, $grade);
    }

    public function testItGetsId()
    {
        $expected = 'expected';
        $grade = new LtiLineItem(['id' => $expected]);

        $result = $grade->getId();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsId()
    {
        $expected = 'expected';

        $this->lineItem->setId($expected);

        $this->assertEquals($expected, $this->lineItem->getId());
    }

    public function testItGetsScoreMaximum()
    {
        $expected = 100;
        $grade = new LtiLineItem(['scoreMaximum' => $expected]);

        $result = $grade->getScoreMaximum();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsScoreMaximum()
    {
        $expected = 100;

        $this->lineItem->setScoreMaximum($expected);

        $this->assertEquals($expected, $this->lineItem->getScoreMaximum());
    }

    public function testItGetsLabel()
    {
        $expected = 'expected';
        $grade = new LtiLineItem(['label' => $expected]);

        $result = $grade->getLabel();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsLabel()
    {
        $expected = 'expected';

        $this->lineItem->setLabel($expected);

        $this->assertEquals($expected, $this->lineItem->getLabel());
    }

    public function testItGetsResourceId()
    {
        $expected = 'expected';
        $grade = new LtiLineItem(['resourceId' => $expected]);

        $result = $grade->getResourceId();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsResourceId()
    {
        $expected = 'expected';

        $this->lineItem->setResourceId($expected);

        $this->assertEquals($expected, $this->lineItem->getResourceId());
    }

    public function testItGetsResourceLinkId()
    {
        $expected = 'expected';
        $grade = new LtiLineItem(['resourceLinkId' => $expected]);

        $result = $grade->getResourceLinkId();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsResourceLinkId()
    {
        $expected = 'expected';

        $this->lineItem->setResourceLinkId($expected);

        $this->assertEquals($expected, $this->lineItem->getResourceLinkId());
    }

    public function testItGetsTag()
    {
        $expected = 'expected';
        $grade = new LtiLineItem(['tag' => $expected]);

        $result = $grade->getTag();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsTag()
    {
        $expected = 'expected';

        $this->lineItem->setTag($expected);

        $this->assertEquals($expected, $this->lineItem->getTag());
    }

    public function testItGetsStartDateTime()
    {
        $expected = LtiTimestamp::new("2023-01-01T00:00:00+00:00");
        $grade = new LtiLineItem(['startDateTime' => $expected]);

        $result = $grade->getStartDateTime();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsStartDateTime()
    {
        $expected = LtiTimestamp::new("2023-01-01T00:00:00+00:00");

        $this->lineItem->setStartDateTime($expected);

        $this->assertEquals($expected, $this->lineItem->getStartDateTime());
    }

    public function testItGetsEndDateTime()
    {
        $expected = LtiTimestamp::new("2023-01-01T00:00:00+00:00");
        $grade = new LtiLineItem(['endDateTime' => $expected]);

        $result = $grade->getEndDateTime();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsEndDateTime()
    {
        $expected = LtiTimestamp::new("2023-01-01T00:00:00+00:00");

        $this->lineItem->setEndDateTime($expected);

        $this->assertEquals($expected, $this->lineItem->getEndDateTime());
    }

    public function testToArrayWithFullObject()
    {
        $expected = [
            'id'             => 'Id',
            'scoreMaximum'   => 100,
            'label'          => 'Label',
            'resourceId'     => 'ResourceId',
            'resourceLinkId' => 'ResourceLinkId',
            'tag'            => 'Tag',
            'startDateTime'  => LtiTimestamp::new("2023-01-01T00:00:00+00:00")->format(),
            'endDateTime'    => LtiTimestamp::new(new DateTime())->format(),
        ];

        $lineItem = new LtiLineItem($expected);

        $this->assertEquals($expected, $lineItem->toArray());
    }

    public function testToArrayWithEmptyObject()
    {
        $this->assertEquals([], $this->lineItem->toArray());
    }

    public function testItCastsFullObjectToString()
    {
        $expected = [
            'id'             => 'Id',
            'scoreMaximum'   => 100,
            'label'          => 'Label',
            'resourceId'     => 'ResourceId',
            'resourceLinkId' => 'ResourceLinkId',
            'tag'            => 'Tag',
            'startDateTime'  => LtiTimestamp::new("2023-01-01T00:00:00+00:00")->format(),
            'endDateTime'    => LtiTimestamp::new(new DateTime())->format(),
        ];

        $lineItem = new LtiLineItem($expected);

        $this->assertEquals(json_encode($expected), (string)$lineItem);
    }

    public function testItCastsEmptyObjectToString()
    {
        $this->assertEquals('[]', (string)$this->lineItem);
    }
}
