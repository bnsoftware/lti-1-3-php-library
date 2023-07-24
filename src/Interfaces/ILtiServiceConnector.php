<?php

namespace BNSoftware\Lti1p3\Interfaces;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

interface ILtiServiceConnector
{
    public function getAccessToken(ILtiRegistration $registration, array $scopes): string;

    public function makeRequest(IServiceRequest $request): ResponseInterface;

    public function getResponseBody(Response $response): ?array;

    public function makeServiceRequest(
        ILtiRegistration $registration,
        array $scopes,
        IServiceRequest $request,
        bool $shouldRetry = true
    ): array;

    public function getAll(
        ILtiRegistration $registration,
        array $scopes,
        IServiceRequest $request,
        string $key
    ): array;

    public function setDebuggingMode(bool $enable): void;
}
