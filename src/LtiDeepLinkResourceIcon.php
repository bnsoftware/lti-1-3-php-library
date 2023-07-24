<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResourceIcon extends LtiDeepLinkResourceImage
{
    /**
     * @param string $url
     * @param int    $width
     * @param int    $height
     * @return LtiDeepLinkResourceIcon
     */
    public static function new(string $url, int $width, int $height): LtiDeepLinkResourceIcon
    {
        return new LtiDeepLinkResourceIcon($url, $width, $height);
    }
}
