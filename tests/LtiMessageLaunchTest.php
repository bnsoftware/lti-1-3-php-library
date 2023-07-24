<?php

namespace Tests;

use BNSoftware\Lti1p3\Interfaces\ICache;
use BNSoftware\Lti1p3\Interfaces\ICookie;
use BNSoftware\Lti1p3\Interfaces\IDatabase;
use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;
use BNSoftware\Lti1p3\Interfaces\ILtiServiceConnector;
use BNSoftware\Lti1p3\JwksEndpoint;
use BNSoftware\Lti1p3\LtiAssignmentsGradesService;
use BNSoftware\Lti1p3\LtiConstants;
use BNSoftware\Lti1p3\LtiCourseGroupsService;
use BNSoftware\Lti1p3\LtiDeepLink;
use BNSoftware\Lti1p3\LtiException;
use BNSoftware\Lti1p3\LtiMessageLaunch;
use BNSoftware\Lti1p3\LtiNamesRolesProvisioningService;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Psr7\Response;
use Mockery;

class LtiMessageLaunchTest extends TestCase
{
    public const ISSUER_URL = 'https://ltiadvantagevalidator.imsglobal.org';
    public const JWKS_FILE = '/tmp/jwks.json';
    public const CERT_DATA_DIR = __DIR__ . '/data/certification/';
    public const PRIVATE_KEY = __DIR__ . '/data/private.key';
    public const STATE = 'state';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->cache = Mockery::mock(ICache::class);
        $this->cookie = Mockery::mock(ICookie::class);
        $this->database = Mockery::mock(IDatabase::class);
        $this->serviceConnector = Mockery::mock(ILtiServiceConnector::class);
        $this->registration = Mockery::mock(ILtiRegistration::class);

        $this->messageLaunch = new LtiMessageLaunch(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        );

        $this->issuer = [
            'id'               => 'issuer_id',
            'issuer'           => static::ISSUER_URL,
            'client_id'        => 'imstester_3dfad6d',
            'auth_login_url'   => 'https://ltiadvantagevalidator.imsglobal.org/ltitool/oidcauthurl.html',
            'auth_token_url'   => 'https://ltiadvantagevalidator.imsglobal.org/ltitool/authcodejwt.html',
            'alg'              => 'RS256',
            'key_set_url'      => static::JWKS_FILE,
            'kid'              => 'key-id',
            'tool_private_key' => file_get_contents(static::PRIVATE_KEY),
        ];

        $this->key = [
            'version'       => LtiConstants::V1_3,
            'issuer_id'     => $this->issuer['id'],
            'deployment_id' => 'testdeploy',
            'campus_id'     => 1,
        ];

        $this->payload = [
            LtiConstants::MESSAGE_TYPE        => 'LtiResourceLinkRequest',
            LtiConstants::VERSION             => LtiConstants::V1_3,
            LtiConstants::RESOURCE_LINK       => [
                'id'                 => 'd3a2504bba5184799a38f141e8df2335cfa8206d',
                'description'        => null,
                'title'              => null,
                'validation_context' => null,
                'errors'             => [
                    'errors' => [],
                ],
            ],
            'aud'                             => $this->issuer['client_id'],
            'azp'                             => $this->issuer['client_id'],
            LtiConstants::DEPLOYMENT_ID       => $this->key['deployment_id'],
            'exp'                             => Carbon::now()->addDay()->timestamp,
            'iat'                             => Carbon::now()->subDay()->timestamp,
            'iss'                             => $this->issuer['issuer'],
            'nonce'                           => 'nonce-5e73ef2f4c6ea0.93530902',
            'sub'                             => '66b6a854-9f43-4bb2-90e8-6653c9126272',
            LtiConstants::TARGET_LINK_URI     => 'https://lms-api.packback.localhost/api/lti/launch',
            LtiConstants::CONTEXT             => [
                'id'                 => 'd3a2504bba5184799a38f141e8df2335cfa8206d',
                'label'              => 'Canvas Unlauched',
                'title'              => 'Canvas - A Fresh Course That Remains Unlaunched',
                'type'               => [
                    LtiConstants::COURSE_OFFERING,
                ],
                'validation_context' => null,
                'errors'             => [
                    'errors' => [],
                ],
            ],
            LtiConstants::TOOL_PLATFORM       => [
                'guid'                => 'FnwyPrXqSxwv8QCm11UwILpDJMAUPJ9WGn8zcvBM:canvas-lms',
                'name'                => 'Packback Engineering',
                'version'             => 'cloud',
                'product_family_code' => 'canvas',
                'validation_context'  => null,
                'errors'              => [
                    'errors' => [],
                ],
            ],
            LtiConstants::LAUNCH_PRESENTATION => [
                'document_target'    => 'iframe',
                'height'             => 400,
                'width'              => 800,
                'return_url'         => 'https://canvas.localhost/courses/3/external_content/success/external_tool_redirect',
                'locale'             => 'en',
                'validation_context' => null,
                'errors'             => [
                    'errors' => [],
                ],
            ],
            'locale'                          => 'en',
            LtiConstants::ROLES               => [
                LtiConstants::INSTITUTION_ADMINISTRATOR,
                LtiConstants::INSTITUTION_INSTRUCTOR,
                LtiConstants::MEMBERSHIP_INSTRUCTOR,
                LtiConstants::SYSTEM_SYS_ADMIN,
                LtiConstants::SYSTEM_USER,
            ],
            LtiConstants::CUSTOM              => [],
            'errors'                          => [
                'errors' => [],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testItInstantiates()
    {
        $this->assertInstanceOf(LtiMessageLaunch::class, $this->messageLaunch);
    }

    /**
     * @return void
     */
    public function testItCreatesANewInstance()
    {
        $messageLaunch = LtiMessageLaunch::new(
            $this->database,
            $this->cache,
            $this->cookie
        );

        $this->assertInstanceOf(LtiMessageLaunch::class, $messageLaunch);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testItGetsALaunchFromTheCache()
    {
        $this->cache->shouldReceive('getLaunchData')
            ->once()->andReturn($this->payload);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->issuer['client_id']);

        $actual = $this->messageLaunch::fromCache('id_token', $this->database, $this->cache);

        $this->assertInstanceOf(LtiMessageLaunch::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testItValidatesALaunch()
    {
        $params = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($this->payload, false),
            'state'    => static::STATE,
        ];

        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($params['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->issuer['client_id']);
        $this->registration->shouldReceive('getKeySetUrl')
            ->once()->andReturn($this->issuer['key_set_url']);
        $this->serviceConnector->shouldReceive('makeRequest')
            ->once()->andReturn(Mockery::mock(Response::class));
        $this->serviceConnector->shouldReceive('getResponseBody')
            ->once()->andReturn(json_decode(file_get_contents(static::JWKS_FILE), true));
        $this->database->shouldReceive('findDeployment')
            ->once()->andReturn(['a deployment']);
        $this->cache->shouldReceive('cacheLaunchData')
            ->once()->andReturn(true);

        $actual = $this->messageLaunch->validate($params);

        $this->assertInstanceOf(LtiMessageLaunch::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfCookiesAreDisabled()
    {
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($this->payload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn();

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_STATE_NOT_FOUND);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfIdTokenIsMissing()
    {
        $payload = [
            'utf8'  => '✓',
            'state' => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_MISSING_ID_TOKEN);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfJwtIsInvalid()
    {
        $payload = [
            'utf8'     => '✓',
            'id_token' => 'nope',
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_INVALID_ID_TOKEN);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfNonceIsMissing()
    {
        $jwtPayload = $this->payload;
        unset($jwtPayload['nonce']);
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($jwtPayload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_MISSING_NONCE);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfNonceIsInvalid()
    {
        $jwtPayload = $this->payload;
        $jwtPayload['nonce'] = 'schmonze';
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($jwtPayload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(false);

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_INVALID_NONCE);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfMissingRegistration()
    {
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($this->payload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn();

        $this->expectException(LtiException::class);
        $expectedMsg = $this->messageLaunch->getMissingRegistrationErrorMsg(
            $this->issuer['issuer'],
            $this->issuer['client_id']
        );
        $this->expectExceptionMessage($expectedMsg);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfRegistrationClientIdIsWrong()
    {
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($this->payload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn('nope');

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_CLIENT_NOT_REGISTERED);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfKIDIsMissing()
    {
        $jwtHeader = $this->issuer;
        unset($jwtHeader['kid']);
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($this->payload, $jwtHeader),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->payload['aud']);

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_NO_KID);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfDeploymentIdIsMissing()
    {
        $jwtPayload = $this->payload;
        unset($jwtPayload[LtiConstants::DEPLOYMENT_ID]);
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($jwtPayload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->payload['aud']);
        $this->registration->shouldReceive('getKeySetUrl')
            ->once()->andReturn($this->issuer['key_set_url']);
        $this->serviceConnector->shouldReceive('makeRequest')
            ->once()->andReturn(Mockery::mock(Response::class));
        $this->serviceConnector->shouldReceive('getResponseBody')
            ->once()->andReturn(json_decode(file_get_contents(static::JWKS_FILE), true));

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_MISSING_DEPLOYEMENT_ID);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfNoDeployment()
    {
        $jwtPayload = $this->payload;
        $payload = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($jwtPayload, $this->issuer),
            'state'    => static::STATE,
        ];
        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($payload['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->payload['aud']);
        $this->registration->shouldReceive('getKeySetUrl')
            ->once()->andReturn($this->issuer['key_set_url']);
        $this->serviceConnector->shouldReceive('makeRequest')
            ->once()->andReturn(Mockery::mock(Response::class));
        $this->serviceConnector->shouldReceive('getResponseBody')
            ->once()->andReturn(json_decode(file_get_contents(static::JWKS_FILE), true));
        $this->database->shouldReceive('findDeployment')
            ->once()->andReturn();

        $this->expectException(LtiException::class);
        $this->expectExceptionMessage(LtiMessageLaunch::ERR_NO_DEPLOYMENT);

        $actual = $this->messageLaunch->validate($payload);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchFailsIfThePayloadIsInvalid()
    {
        $payload = $this->payload;
        unset($payload[LtiConstants::MESSAGE_TYPE]);
        $params = [
            'utf8'     => '✓',
            'id_token' => $this->buildJWT($payload, $this->issuer),
            'state'    => static::STATE,
        ];

        $this->cookie->shouldReceive('getCookie')
            ->once()->andReturn($params['state']);
        $this->cache->shouldReceive('checkNonceIsValid')
            ->once()->andReturn(true);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->issuer['client_id']);
        $this->registration->shouldReceive('getKeySetUrl')
            ->once()->andReturn($this->issuer['key_set_url']);
        $this->serviceConnector->shouldReceive('makeRequest')
            ->once()->andReturn(Mockery::mock(Response::class));
        $this->serviceConnector->shouldReceive('getResponseBody')
            ->once()->andReturn(json_decode(file_get_contents(static::JWKS_FILE), true));
        $this->database->shouldReceive('findDeployment')
            ->once()->andReturn(['a deployment']);

        $this->expectException(LtiException::class);

        $this->messageLaunch->validate($params);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchHasNrps()
    {
        $payload = $this->payload;
        $payload[LtiConstants::NRPS_CLAIM_SERVICE]['context_memberships_url'] = 'https://example.com';
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasNrps();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchDoesNotHaveNrps()
    {
        $payload = $this->payload;
        unset($payload[LtiConstants::NRPS_CLAIM_SERVICE]['context_memberships_url']);
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasNrps();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testGetNrpsForALaunch()
    {
        $payload = $this->payload;
        $payload[LtiConstants::NRPS_CLAIM_SERVICE]['context_memberships_url'] = 'https://example.com';
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->getNrps();

        $this->assertInstanceOf(LtiNamesRolesProvisioningService::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchHasGs()
    {
        $payload = $this->payload;
        $payload[LtiConstants::GS_CLAIM_SERVICE]['context_groups_url'] = 'https://example.com';
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasGs();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchDoesNotHaveGs()
    {
        $payload = $this->payload;
        unset($payload[LtiConstants::GS_CLAIM_SERVICE]['context_groups_url']);
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasGs();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testGetGsForALaunch()
    {
        $payload = $this->payload;
        $payload[LtiConstants::GS_CLAIM_SERVICE]['context_groups_url'] = 'https://example.com';
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->getGs();

        $this->assertInstanceOf(LtiCourseGroupsService::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchHasAgs()
    {
        $payload = $this->payload;
        $payload[LtiConstants::AGS_CLAIM_ENDPOINT] = ['https://example.com'];
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasAgs();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchDoesNotHaveAgs()
    {
        $payload = $this->payload;
        unset($payload[LtiConstants::AGS_CLAIM_ENDPOINT]);
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->hasAgs();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testGetAgsForALaunch()
    {
        $payload = $this->payload;
        $payload[LtiConstants::AGS_CLAIM_ENDPOINT] = ['https://example.com'];
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->getAgs();

        $this->assertInstanceOf(LtiAssignmentsGradesService::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsADeepLink()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_DEEPLINK;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isDeepLinkLaunch();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsNotADeepLink()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_SUBMISSION_REVIEW;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isDeepLinkLaunch();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testGetDeepLinkForALaunch()
    {
        $payload = $this->payload;
        $payload[LtiConstants::DEPLOYMENT_ID] = 'deployment_id';
        $payload[LtiConstants::DL_DEEP_LINK_SETTINGS] = [];
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->getDeepLink();

        $this->assertInstanceOf(LtiDeepLink::class, $actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsASubmissionReview()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_SUBMISSION_REVIEW;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isSubmissionReviewLaunch();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsNotASubmissionReview()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_DEEPLINK;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isSubmissionReviewLaunch();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsAResourceLink()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_RESOURCE_LINK;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isResourceLaunch();

        $this->assertTrue($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testALaunchIsNotAResourceLink()
    {
        $payload = $this->payload;
        $payload[LtiConstants::MESSAGE_TYPE] = LtiMessageLaunch::TYPE_DEEPLINK;
        $launch = $this->fakeLaunch($payload);

        $actual = $launch->isResourceLaunch();

        $this->assertFalse($actual);
    }

    /**
     * @return void
     * @throws LtiException
     */
    public function testGetLaunchDataForALaunch()
    {
        $launch = $this->fakeLaunch($this->payload);

        $actual = $launch->getLaunchData();

        $this->assertEquals($this->payload, $actual);
    }

    /**
     * @param array $payload
     * @return LtiMessageLaunch
     * @throws LtiException
     */
    private function fakeLaunch(array $payload): LtiMessageLaunch
    {
        $this->cache->shouldReceive('getLaunchData')
            ->once()->andReturn($payload);
        $this->database->shouldReceive('findRegistrationByIssuer')
            ->once()->andReturn($this->registration);
        $this->registration->shouldReceive('getClientId')
            ->once()->andReturn($this->issuer['client_id']);

        return $this->messageLaunch::fromCache(
            'id_token',
            $this->database,
            $this->cache,
            $this->cookie,
            $this->serviceConnector
        );
    }

    /**
     * @param array $data
     * @param       $header
     * @return string
     */
    private function buildJWT(array $data, $header = null): string
    {
        $jwks = json_encode(
            JwksEndpoint::new(
                [
                    $this->issuer['kid'] => $this->issuer['tool_private_key'],
                ]
            )->getPublicJwks()
        );
        file_put_contents(static::JWKS_FILE, $jwks);

        // If we pass in a header, use that instead of creating one automatically based on params given
        if ($header) {
            $segments = [];
            $segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($header));
            $segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($data));
            $signing_input = implode('.', $segments);

            $signature = JWT::sign($signing_input, $this->issuer['tool_private_key'], $this->issuer['alg']);
            $segments[] = JWT::urlsafeB64Encode($signature);

            return implode('.', $segments);
        }

        return JWT::encode($data, $this->issuer['tool_private_key'], $this->issuer['alg'], $this->issuer['kid']);
    }
}
