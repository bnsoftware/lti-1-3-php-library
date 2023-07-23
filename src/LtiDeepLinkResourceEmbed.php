<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResourceEmbed
{
    private string $html;

    /**
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html = $html;
    }

    /**
     * @param string $html
     * @return LtiDeepLinkResourceEmbed
     */
    public static function new(string $html): LtiDeepLinkResourceEmbed
    {
        return new LtiDeepLinkResourceEmbed($html);
    }

    /**
     * @param string $html
     * @return $this
     */
    public function setHtml(string $html): LtiDeepLinkResourceEmbed
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'html' => $this->html,
        ];
    }
}
