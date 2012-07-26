<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SocialLogin\Core;

/**
 * Facebook auth provider
 *
 */
class FacebookAuthProvider extends AAuthProvider
{

    /**
     * Unique auth provider name
     */
    const PROVIDER_NAME = 'facebook';

    /**
     * Url to which user will be redirected
     */
    const AUTH_REQUEST_URL = 'https://www.facebook.com/dialog/oauth';

    /**
     * Data to gain access to
     */
    const AUTH_REQUEST_SCOPE = 'email';

    /**
     * Url to get access token
     */
    const TOKEN_REQUEST_URL = 'https://graph.facebook.com/oauth/access_token';

    /**
     * Url to access user profile information
     */
    const PROFILE_REQUEST_URL = 'https://graph.facebook.com/me';

    /**
     * Path of the icon to be displayed in site header
     */
    const SMALL_ICON_PATH = 'modules/CDev/SocialLogin/icons/facebook_small.png';

    /**
     * Returns access token based on authorization code
     *
     * @param string $code Authorization code
     *
     * @return string
     */
    protected function getAccessToken($code)
    {
        $request = new \XLite\Core\HTTP\Request($this->getTokenRequestUrl($code));
        $response = $request->sendRequest();

        $accessToken = null;

        if (200 == $response->code) {
            parse_str($response->body, $data);
            $accessToken = $data['access_token'];
        }

        return $accessToken;
    }

    /**
     * Get OAuth 2.0 client ID
     *
     * @return string
     */
    protected function getClientId()
    {
        return \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_id;
    }

    /**
     * Get OAuth 2.0 client secret
     *
     * @return string
     */
    protected function getClientSecret()
    {
        return \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_secret;
    }
}
