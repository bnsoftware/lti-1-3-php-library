<?php

namespace BNSoftware\Lti1p3\ImsStorage;

use BNSoftware\Lti1p3\Interfaces\ICookie;

class ImsCookie implements ICookie
{
    /**
     * @param string $name
     * @return string|null
     */
    public function getCookie(string $name): ?string
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        // Look for backup cookie if same site is not supported by the user's browser.
        if (isset($_COOKIE['LEGACY_'.$name])) {
            return $_COOKIE['LEGACY_'.$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $value
     * @param int    $exp
     * @param array  $options
     * @return void
     */
    public function setCookie(string $name, string $value, int $exp = 3600, array $options = []): void
    {
        $cookie_options = [
            'expires' => time() + $exp,
        ];

        // SameSite none and secure will be required for tools to work inside iframes
        $same_site_options = [
            'samesite' => 'None',
            'secure' => true,
        ];

        setcookie($name, $value, array_merge($cookie_options, $same_site_options, $options));

        // Set a second fallback cookie in the event that "SameSite" is not supported
        setcookie('LEGACY_'.$name, $value, array_merge($cookie_options, $options));
    }
}
