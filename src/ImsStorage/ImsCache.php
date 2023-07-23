<?php

namespace BNSoftware\Lti1p3\ImsStorage;

use BNSoftware\Lti1p3\Interfaces\ICache;

class ImsCache implements ICache
{
    private array $cache;

    /**
     * @param string $key
     * @return array|null
     */
    public function getLaunchData(string $key): ?array
    {
        $this->loadCache();

        return $this->cache[$key] ?? null;
    }

    /**
     * @param string $key
     * @param array  $jwtBody
     * @return void
     */
    public function cacheLaunchData(string $key, array $jwtBody): void
    {
        $this->loadCache();

        $this->cache[$key] = $jwtBody;
        $this->saveCache();
    }

    /**
     * @param string $nonce
     * @param string $state
     * @return void
     */
    public function cacheNonce(string $nonce, string $state): void
    {
        $this->loadCache();

        $this->cache['nonce'][$nonce] = $state;
        $this->saveCache();
    }

    /**
     * @param string $nonce
     * @param string $state
     * @return bool
     */
    public function checkNonceIsValid(string $nonce, string $state): bool
    {
        $this->loadCache();

        return isset($this->cache['nonce'][$nonce]) &&
            $this->cache['nonce'][$nonce] === $state;
    }

    /**
     * @param string $key
     * @param string $accessToken
     * @return void
     */
    public function cacheAccessToken(string $key, string $accessToken): void
    {
        $this->loadCache();

        $this->cache[$key] = $accessToken;
        $this->saveCache();
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getAccessToken(string $key): ?string
    {
        $this->loadCache();

        return $this->cache[$key] ?? null;
    }

    /**
     * @param string $key
     * @return void
     */
    public function clearAccessToken(string $key): void
    {
        $this->loadCache();

        unset($this->cache[$key]);
        $this->saveCache();
    }

    /**
     * @return void
     */
    private function loadCache()
    {
        $cache = file_get_contents(sys_get_temp_dir().'/lti_cache.txt');
        if (empty($cache)) {
            file_put_contents(sys_get_temp_dir().'/lti_cache.txt', '{}');
        }
        $this->cache = json_decode($cache, true);
    }

    /**
     * @return void
     */
    private function saveCache()
    {
        file_put_contents(sys_get_temp_dir().'/lti_cache.txt', json_encode($this->cache));
    }
}
