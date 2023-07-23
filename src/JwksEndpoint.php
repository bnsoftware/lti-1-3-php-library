<?php

namespace BNSoftware\Lti1p3;

use BNSoftware\Lti1p3\Interfaces\IDatabase;
use BNSoftware\Lti1p3\Interfaces\ILtiRegistration;
use phpseclib3\Crypt\RSA;

class JwksEndpoint
{
    private array $keys;

    /**
     * @param array $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @param array $keys
     * @return JwksEndpoint
     */
    public static function new(array $keys): JwksEndpoint
    {
        return new JwksEndpoint($keys);
    }

    /**
     * @param IDatabase $database
     * @param           $issuer
     * @return JwksEndpoint
     */
    public static function fromIssuer(IDatabase $database, $issuer): JwksEndpoint
    {
        $registration = $database->findRegistrationByIssuer($issuer);

        return new JwksEndpoint([$registration->getKid() => $registration->getToolPrivateKey()]);
    }

    /**
     * @param ILtiRegistration $registration
     * @return JwksEndpoint
     */
    public static function fromRegistration(ILtiRegistration $registration): JwksEndpoint
    {
        return new JwksEndpoint([$registration->getKid() => $registration->getToolPrivateKey()]);
    }

    /**
     * @return array[]
     */
    public function getPublicJwks(): array
    {
        $jwks = [];
        foreach ($this->keys as $kid => $privateKey) {
            $key = RSA::load($privateKey);
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $jwk = json_decode($key->getPublicKey()->toString('JWK'), true);
            $jwks[] = array_merge(
                $jwk['keys'][0],
                [
                    'alg' => 'RS256',
                    'use' => 'sig',
                    'kid' => $kid,
                ]
            );
        }

        return ['keys' => $jwks];
    }
}
