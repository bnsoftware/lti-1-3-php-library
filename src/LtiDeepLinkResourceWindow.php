<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResourceWindow
{
    private ?string $targetName;
    private ?int $width;
    private ?int $height;
    private ?string $windowFeatures;

    /**
     * @param string|null $targetName
     * @param int|null    $width
     * @param int|null    $height
     * @param string|null $windowFeatures
     */
    public function __construct(
        ?string $targetName = null,
        ?int $width = null,
        ?int $height = null,
        ?string $windowFeatures = null
    ) {
        $this->targetName = $targetName;
        $this->width = $width;
        $this->height = $height;
        $this->windowFeatures = $windowFeatures;
    }

    /**
     * @param string|null $targetName
     * @param int|null    $width
     * @param int|null    $height
     * @param string|null $windowFeatures
     * @return LtiDeepLinkResourceWindow
     */
    public static function new(
        ?string $targetName = null,
        ?int $width = null,
        ?int $height = null,
        ?string $windowFeatures = null
    ): LtiDeepLinkResourceWindow {
        return new LtiDeepLinkResourceWindow($targetName, $width, $height, $windowFeatures);
    }

    /**
     * @param string|null $targetName
     * @return $this
     */
    public function setTargetName(?string $targetName): LtiDeepLinkResourceWindow
    {
        $this->targetName = $targetName;

        return $this;
    }

    /**
     * @return ?string
     */
    public function getTargetName(): ?string
    {
        return $this->targetName;
    }

    /**
     * @param ?int $width
     * @return $this
     */
    public function setWidth(?int $width): LtiDeepLinkResourceWindow
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param ?int $height
     * @return $this
     */
    public function setHeight(?int $height): LtiDeepLinkResourceWindow
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param string|null $windowFeatures
     * @return $this
     */
    public function setWindowFeatures(?string $windowFeatures): LtiDeepLinkResourceWindow
    {
        $this->windowFeatures = $windowFeatures;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWindowFeatures(): ?string
    {
        return $this->windowFeatures;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'targetName'     => $this->targetName,
                'width'          => $this->width,
                'height'         => $this->height,
                'windowFeatures' => $this->windowFeatures,
            ],
            '\BNSoftware\Lti1p3\Helpers\Helpers::checkIfNullValue'
        );
    }
}
