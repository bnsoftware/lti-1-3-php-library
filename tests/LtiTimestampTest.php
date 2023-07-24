<?php

namespace Tests;

use BNSoftware\Lti1p3\LtiTimestamp;
use DateTime;
use PHPUnit\Framework\TestCase;

class LtiTimestampTest extends TestCase
{
    private LtiTimestamp $timestamp;

    public function setUp(): void
    {
        $this->timestamp = new LtiTimestamp();
    }

    public function testItInstantiates()
    {
        $this->assertInstanceOf(LtiTimestamp::class, $this->timestamp);
    }

    public function testItCreatesANewInstance()
    {
        $timestamp = LtiTimestamp::new();

        $this->assertInstanceOf(LtiTimestamp::class, $timestamp);
    }

    public function testItGetsTimestamp()
    {
        $expected = new DateTime('2023-01-01T00:00:00Z');
        $timestamp = new LtiTimestamp($expected);

        $result = $timestamp->getTimestamp();

        $this->assertEquals($expected, $result);
    }

    public function testItSetsTimestamp()
    {
        $expected = new DateTime('2023-01-01T00:00:00Z');
        $this->timestamp->setTimestamp($expected);

        $this->assertEquals($expected, $this->timestamp->getTimestamp());
    }

    public function testItCastsToString()
    {
        $expected = $this->timestamp->format();

        $this->assertEquals($expected, (string)$this->timestamp);
    }

    public function testFormatOutput()
    {
        $this->timestamp->setTimestamp(new DateTime('2023-01-01T00:00:00Z'));

        $this->assertEquals('2023-01-01T00:00:00.000Z', $this->timestamp->format());
    }
}
