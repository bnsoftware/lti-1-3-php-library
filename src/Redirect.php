<?php

namespace BNSoftware\Lti1p3;

use BNSoftware\Lti1p3\Interfaces\ICookie;

class Redirect
{
    private string $location;
    private ?string $refererQuery;
    private static string $CAN_302_COOKIE = 'LTI_302_Redirect';

    /**
     * @param string      $location
     * @param string|null $refererQuery
     */
    public function __construct(string $location, string $refererQuery = null)
    {
        $this->location = $location;
        $this->refererQuery = $refererQuery;
    }

    /**
     * @return void
     */
    public function doRedirect(): void
    {
        header('Location: ' . $this->location, true, 302);
        exit;
    }

    /**
     * @param ICookie $cookie
     * @return void
     */
    public function doHybridRedirect(ICookie $cookie): void
    {
        if (empty($cookie->getCookie(self::$CAN_302_COOKIE))) {
            $cookie->setCookie(self::$CAN_302_COOKIE, 'true');
            $this->doJsRedirect();
        } else {
            $this->doRedirect();
        }
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->location;
    }

    /**
     * @return void
     */
    public function doJsRedirect(): void
    {
        ?>
        <a id="try-again" target="_blank">If you are not automatically redirected, click here to continue</a>
        <script>
            document.getElementById('try-again').href =<?php
            if (empty($this->refererQuery)) {
                echo 'window.location.href';
            } else {
                echo "window.location.origin + window.location.pathname + '?" . $this->refererQuery . "'";
            } ?>;

            const canAccessCookies = function () {
                if (!navigator.cookieEnabled) {
                    // We don't have access
                    return false;
                }
                // Firefox returns true even if we don't actually have access
                try {
                    if (
                        !document.cookie
                        || document.cookie === ""
                        || document.cookie.indexOf('<?php echo self::$CAN_302_COOKIE; ?>') === -1
                    ) {
                        return false;
                    }
                } catch (e) {
                    return false;
                }
                return true;
            };

            if (canAccessCookies()) {
                // We have access, continue with redirect
                window.location = '<?php echo $this->location; ?>';
            } else {
                // We don't have access, reopen flow in a new window.
                const opened = window.open(document.getElementById('try-again').href, '_blank');
                if (opened) {
                    document.getElementById('try-again').innerText = "New window opened, click to reopen";
                } else {
                    document.getElementById('try-again').innerText = "Popup blocked, click to open in a new window";
                }
            }
        </script>
        <?php
    }
}
