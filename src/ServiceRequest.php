<?php

namespace BNSoftware\Lti1p3;

use BNSoftware\Lti1p3\Interfaces\IServiceRequest;

class ServiceRequest implements IServiceRequest
{
    // Request methods
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';

    // Request types
    public const TYPE_UNSUPPORTED = 'unsupported';
    public const TYPE_AUTH = 'auth';

    // MessageLaunch
    public const TYPE_GET_KEYSET = 'get_keyset';

    // AGS
    public const TYPE_GET_GRADES = 'get_grades';
    public const TYPE_SYNC_GRADE = 'sync_grades';
    public const TYPE_CREATE_LINE_ITEM = 'create_lineitem';
    public const TYPE_DELETE_LINE_ITEM = 'delete_lineitem';
    public const TYPE_GET_LINE_ITEMS = 'get_lineitems';
    public const TYPE_GET_LINE_ITEM = 'get_lineitem';
    public const TYPE_UPDATE_LINE_ITEM = 'update_lineitem';

    // CGS
    public const TYPE_GET_GROUPS = 'get_groups';
    public const TYPE_GET_SETS = 'get_sets';

    // NRPS
    public const TYPE_GET_MEMBERSHIPS = 'get_memberships';

    private string $method;
    private string $url;
    private string $type;
    private ?string $body;
    private array $payload;
    private ?string $accessToken;
    private string $contentType = 'application/json';
    private string $accept = 'application/json';

    /**
     * @param string $method
     * @param string $url
     * @param string $type
     */
    public function __construct(string $method, string $url, string $type = self::TYPE_UNSUPPORTED)
    {
        $this->method = $method;
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * @param string $method
     * @return IServiceRequest
     */
    public function setMethod(string $method): IServiceRequest
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtoupper($this->method);
    }

    /**
     * @param string $url
     * @return IServiceRequest
     */
    public function setUrl(string $url): IServiceRequest
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array $payload
     * @return IServiceRequest
     */
    public function setPayload(array $payload): IServiceRequest
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return array[]
     */
    public function getPayload(): array
    {
        if (isset($this->payload)) {
            return $this->payload;
        }

        $payload = [
            'headers' => $this->getHeaders(),
        ];

        $body = $this->getBody();
        if ($body) {
            $payload['body'] = $body;
        }

        return $payload;
    }

    /**
     * @param string $accessToken
     * @return IServiceRequest
     */
    public function setAccessToken(string $accessToken): IServiceRequest
    {
        $this->accessToken = 'Bearer ' . $accessToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string|null $body
     * @return IServiceRequest
     */
    public function setBody(?string $body): IServiceRequest
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string|null
     */
    private function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $contentType
     * @return IServiceRequest
     */
    public function setContentType(string $contentType): IServiceRequest
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string
     */
    private function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $accept
     * @return IServiceRequest
     */
    public function setAccept(string $accept): IServiceRequest
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * @return string
     */
    private function getAccept(): string
    {
        return $this->accept;
    }

    /**
     * @return string
     */
    public function getErrorPrefix(): string
    {
        $defaultMessage = 'Logging request data:';
        $errorMessages = [
            static::TYPE_UNSUPPORTED      => $defaultMessage,
            static::TYPE_AUTH             => 'Authenticating:',
            static::TYPE_GET_KEYSET       => 'Getting key set:',
            static::TYPE_GET_GRADES       => 'Getting grades:',
            static::TYPE_SYNC_GRADE       => 'Syncing grade for this lti_user_id:',
            static::TYPE_CREATE_LINE_ITEM => 'Creating line item:',
            static::TYPE_DELETE_LINE_ITEM => 'Deleting line item:',
            static::TYPE_GET_LINE_ITEMS   => 'Getting line items:',
            static::TYPE_GET_LINE_ITEM    => 'Getting a line item:',
            static::TYPE_UPDATE_LINE_ITEM => 'Updating line item:',
            static::TYPE_GET_GROUPS       => 'Getting groups:',
            static::TYPE_GET_SETS         => 'Getting sets:',
            static::TYPE_GET_MEMBERSHIPS  => 'Getting memberships:',
        ];

        return $errorMessages[$this->type] ?? $defaultMessage;
    }

    /**
     * @return string[]
     */
    private function getHeaders(): array
    {
        $headers = [
            'Accept' => $this->accept,
        ];

        if (isset($this->accessToken)) {
            $headers['Authorization'] = $this->accessToken;
        }

        // Include Content-Type for POST and PUT requests
        if (in_array($this->getMethod(), [ServiceRequest::METHOD_POST, ServiceRequest::METHOD_PUT])) {
            $headers['Content-Type'] = $this->contentType;
        }

        return $headers;
    }
}
