<?php

namespace BNSoftware\Lti1p3;

class LtiDeepLinkResource
{
    private string $type = LtiConstants::DL_RESOURCE_LINK_TYPE;
    private ?string $title = null;
    private ?string $text = null;
    private ?string $url = null;
    private ?LtiLineItem $lineItem = null;
    private ?LtiDeepLinkResourceIcon $icon = null;
    private ?LtiDeepLinkResourceThumbnail $thumbnail = null;
    private array $customParams = [];
    private string $target = LtiConstants::DL_RESOURCE_TARGET_IFRAME;
    private ?LtiDeepLinkResourceIframe $iframe = null;
    private ?LtiDeepLinkResourceWindow $window = null;
    private ?LtiDeepLinkDateTimeInterval $availabilityInterval = null;
    private ?LtiDeepLinkDateTimeInterval $submissionInterval = null;

    /** ScreenPal customizations */
    private ?string $html;
    private ?LtiDeepLinkResourceEmbed $embed;

    public static function new(): LtiDeepLinkResource
    {
        return new LtiDeepLinkResource();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $value): LtiDeepLinkResource
    {
        $this->type = $value;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $value): LtiDeepLinkResource
    {
        $this->title = $value;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $value): LtiDeepLinkResource
    {
        $this->text = $value;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $value): LtiDeepLinkResource
    {
        $this->url = $value;

        return $this;
    }

    public function getLineItem(): ?LtiLineitem
    {
        return $this->lineItem;
    }

    public function setLineItem(?LtiLineitem $value): LtiDeepLinkResource
    {
        $this->lineItem = $value;

        return $this;
    }

    public function getIcon(): ?LtiDeepLinkResourceIcon
    {
        return $this->icon;
    }

    public function setIcon(?LtiDeepLinkResourceIcon $icon): LtiDeepLinkResource
    {
        $this->icon = $icon;

        return $this;
    }

    public function getThumbnail(): ?LtiDeepLinkResourceThumbnail
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?LtiDeepLinkResourceThumbnail $thumbnail): LtiDeepLinkResource
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getCustomParams(): array
    {
        return $this->customParams;
    }

    public function setCustomParams(array $value): LtiDeepLinkResource
    {
        $this->customParams = $value;

        return $this;
    }

    /**
     * @deprecated This field maps the "presentation" resource property, which is non-standard.
     * Consider using "iframe" and/or "window" instead.
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @deprecated This field maps the "presentation" resource property, which is non-standard.
     * Consider using "iframe" and/or "window" instead.
     */
    public function setTarget(string $value): LtiDeepLinkResource
    {
        $this->target = $value;

        return $this;
    }

    public function getIframe(): ?LtiDeepLinkResourceIframe
    {
        return $this->iframe;
    }

    public function setIframe(?LtiDeepLinkResourceIframe $iframe): LtiDeepLinkResource
    {
        $this->iframe = $iframe;

        return $this;
    }

    public function getWindow(): ?LtiDeepLinkResourceWindow
    {
        return $this->window;
    }

    public function setWindow(?LtiDeepLinkResourceWindow $window): LtiDeepLinkResource
    {
        $this->window = $window;

        return $this;
    }

    public function getAvailabilityInterval(): ?LtiDeepLinkDateTimeInterval
    {
        return $this->availabilityInterval;
    }

    public function setAvailabilityInterval(?LtiDeepLinkDateTimeInterval $availabilityInterval): LtiDeepLinkResource
    {
        $this->availabilityInterval = $availabilityInterval;

        return $this;
    }

    public function getSubmissionInterval(): ?LtiDeepLinkDateTimeInterval
    {
        return $this->submissionInterval;
    }

    public function setSubmissionInterval(?LtiDeepLinkDateTimeInterval $submissionInterval): LtiDeepLinkResource
    {
        $this->submissionInterval = $submissionInterval;

        return $this;
    }

    /**
     * @throws LtiException
     */
    public function toArray(): array
    {
        $resource = [
            'type' => $this->type,
        ];

        if (isset($this->title)) {
            $resource['title'] = $this->title;
        }
        if (isset($this->text)) {
            $resource['text'] = $this->text;
        }
        if (isset($this->url)) {
            $resource['url'] = $this->url;
        }
        if (!empty($this->customParams)) {
            $resource['custom'] = $this->customParams;
        }
        if (isset($this->icon)) {
            $resource['icon'] = $this->icon->toArray();
        }
        if (isset($this->thumbnail)) {
            $resource['thumbnail'] = $this->thumbnail->toArray();
        }

        // Kept for backwards compatibility
        if (!isset($this->iframe) && !isset($this->window)) {
            $resource['presentation'] = [
                'documentTarget' => $this->target,
            ];
        }

        if (isset($this->iframe)) {
            $resource['iframe'] = $this->iframe->toArray();
        }
        if (isset($this->window)) {
            $resource['window'] = $this->window->toArray();
        }
        if (isset($this->availabilityInterval)) {
            $resource['available'] = $this->availabilityInterval->toArray();
        }
        if (isset($this->submissionInterval)) {
            $resource['submission'] = $this->submissionInterval->toArray();
        }

        /** ScreenPal customizations */
        if (!empty($this->html)) {
            $resource['html'] = $this->html;
        }
        if (!empty($this->embed)) {
            $resource['embed'] = $this->embed->toArray();
        }
        if ($this->lineItem !== null) {
            $resource['lineItem'] = $this->lineItem->toArray();
        }

        return $resource;
    }

    /** ScreenPal customizations */
    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): LtiDeepLinkResource
    {
        $this->html = $html;

        return $this;
    }

    public function getEmbed(): ?LtiDeepLinkResourceEmbed
    {
        return $this->embed;
    }

    public function setEmbed(?LtiDeepLinkResourceEmbed $embed): LtiDeepLinkResource
    {
        $this->embed = $embed;

        return $this;
    }
}
