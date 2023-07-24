<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResourceThumbnail extends LtiDeepLinkResourceImage
{
    /**
     * @param string $url
     * @param int    $width
     * @param int    $height
     * @return LtiDeepLinkResourceThumbnail
     */
    public static function new(string $url, int $width, int $height): LtiDeepLinkResourceThumbnail
    {
        return new LtiDeepLinkResourceThumbnail($url, $width, $height);
    }
}
