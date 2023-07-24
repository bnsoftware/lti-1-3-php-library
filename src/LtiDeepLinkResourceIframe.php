<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResourceIframe
{
    private ?string $src;
    private ?int $width;
    private ?int $height;

    /**
     * @param string|null $src
     * @param int|null    $width
     * @param int|null    $height
     */
    public function __construct(?string $src = null, ?int $width = null, ?int $height = null)
    {
        $this->src = $src;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @param string|null $src
     * @param int|null    $width
     * @param int|null    $height
     * @return LtiDeepLinkResourceIframe
     */
    public static function new(?string $src = null, ?int $width = null, ?int $height = null): LtiDeepLinkResourceIframe
    {
        return new LtiDeepLinkResourceIframe($src, $width, $height);
    }

    /**
     * @param string|null $src
     * @return $this
     */
    public function setSrc(?string $src): LtiDeepLinkResourceIframe
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSrc(): ?string
    {
        return $this->src;
    }

    /**
     * @param int|null $width
     * @return $this
     */
    public function setWidth(?int $width): LtiDeepLinkResourceIframe
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $height
     * @return $this
     */
    public function setHeight(?int $height): LtiDeepLinkResourceIframe
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter(
            [
                'src'    => $this->src,
                'width'  => $this->width,
                'height' => $this->height,
            ],
            '\BNSoftware\Lti1p3\Helpers\Helpers::checkIfNullValue'
        );
    }
}
