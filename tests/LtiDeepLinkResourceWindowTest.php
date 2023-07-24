<?php

namespace Tests;

use BNSoftware\Lti1p3\LtiDeepLinkResourceWindow;

class LtiDeepLinkResourceWindowTest extends TestCase
{
    public const INITIAL_TARGET_NAME = 'example-name';
    public const INITIAL_WIDTH = 1;
    public const INITIAL_HEIGHT = 2;
    public const INITIAL_WINDOW_FEATURES = 'example-feature=value';
    private LtiDeepLinkResourceWindow $ltiDeepLinkResourceWindow;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->ltiDeepLinkResourceWindow = new LtiDeepLinkResourceWindow(
            self::INITIAL_TARGET_NAME,
            self::INITIAL_WIDTH,
            self::INITIAL_HEIGHT,
            self::INITIAL_WINDOW_FEATURES
        );
    }

    /**
     * @return void
     */
    public function testItInstantiates()
    {
        $this->assertInstanceOf(LtiDeepLinkResourceWindow::class, $this->ltiDeepLinkResourceWindow);
    }

    /**
     * @return void
     */
    public function testItCreatesANewInstance()
    {
        $deepLinkResource = LtiDeepLinkResourceWindow::new();

        $this->assertInstanceOf(LtiDeepLinkResourceWindow::class, $deepLinkResource);
    }

    /**
     * @return void
     */
    public function testItGetsTargetName()
    {
        $result = $this->ltiDeepLinkResourceWindow->getTargetName();

        $this->assertEquals(self::INITIAL_TARGET_NAME, $result);
    }

    /**
     * @return void
     */
    public function testItSetsTargetName()
    {
        $expected = 'expected';

        $result = $this->ltiDeepLinkResourceWindow->setTargetName($expected);

        $this->assertSame($this->ltiDeepLinkResourceWindow, $result);
        $this->assertEquals($expected, $this->ltiDeepLinkResourceWindow->getTargetName());
    }

    /**
     * @return void
     */
    public function testItGetsWidth()
    {
        $result = $this->ltiDeepLinkResourceWindow->getWidth();

        $this->assertEquals(self::INITIAL_WIDTH, $result);
    }

    /**
     * @return void
     */
    public function testItSetsWidth()
    {
        $expected = 300;

        $result = $this->ltiDeepLinkResourceWindow->setWidth($expected);

        $this->assertSame($this->ltiDeepLinkResourceWindow, $result);
        $this->assertEquals($expected, $this->ltiDeepLinkResourceWindow->getWidth());
    }

    /**
     * @return void
     */
    public function testItGetsHeight()
    {
        $result = $this->ltiDeepLinkResourceWindow->getHeight();

        $this->assertEquals(self::INITIAL_HEIGHT, $result);
    }

    /**
     * @return void
     */
    public function testItSetsHeight()
    {
        $expected = 400;

        $result = $this->ltiDeepLinkResourceWindow->setHeight($expected);

        $this->assertSame($this->ltiDeepLinkResourceWindow, $result);
        $this->assertEquals($expected, $this->ltiDeepLinkResourceWindow->getHeight());
    }

    /**
     * @return void
     */
    public function testItGetsWindowFeatures()
    {
        $result = $this->ltiDeepLinkResourceWindow->getWindowFeatures();

        $this->assertEquals(self::INITIAL_WINDOW_FEATURES, $result);
    }

    /**
     * @return void
     */
    public function testItSetsWindowFeatures()
    {
        $expected = 'first-feature=value,second-feature';

        $result = $this->ltiDeepLinkResourceWindow->setWindowFeatures($expected);

        $this->assertSame($this->ltiDeepLinkResourceWindow, $result);
        $this->assertEquals($expected, $this->ltiDeepLinkResourceWindow->getWindowFeatures());
    }

    /**
     * @return void
     */
    public function testItCreatesArray()
    {
        $expected = [
            'targetName'     => 'target-name',
            'width'          => 100,
            'height'         => 200,
            'windowFeatures' => 'first-feature=value,second-feature',
        ];

        $this->ltiDeepLinkResourceWindow->setTargetName($expected['targetName']);
        $this->ltiDeepLinkResourceWindow->setWidth($expected['width']);
        $this->ltiDeepLinkResourceWindow->setHeight($expected['height']);
        $this->ltiDeepLinkResourceWindow->setWindowFeatures($expected['windowFeatures']);

        $result = $this->ltiDeepLinkResourceWindow->toArray();

        $this->assertEquals($expected, $result);
    }
}
