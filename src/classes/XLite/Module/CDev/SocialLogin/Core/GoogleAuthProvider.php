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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.24
 */

namespace XLite\Module\CDev\SocialLogin\Core;

/**
 * Google auth provider
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
class GoogleAuthProvider extends AAuthProvider
{
    
    /**
     * Unique auth provider name
     */
    const PROVIDER_NAME = 'google';

    /**
     * Url to which user will be redirected
     */
    const AUTH_REQUEST_URL = 'https://accounts.google.com/o/oauth2/auth';

    /**
     * Data to gain access to
     */
    const AUTH_REQUEST_SCOPE = 'https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email';

    /**
     * Url to get access token
     */
    const TOKEN_REQUEST_URL = 'https://accounts.google.com/o/oauth2/token';

    /**
     * Url to access user profile information 
     */
    const PROFILE_REQUEST_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';

    /**
     * Path of the icon to be displayed in site header 
     */
    const SMALL_ICON_PATH = 'modules/CDev/SocialLogin/icons/google_small.png';

    /**
     * Process authorization grant and return array with profile data
     * 
     * @return array Client information containing at least id and e-mail
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function processAuth()
    {
        $profile = parent::processAuth();

        if (isset($profile['email'])) {
            $profile['id'] = $profile['email'];
        }

        return $profile;
    }

    /**
     * Returns access token based on authorization code
     * 
     * @param string $code Authorization code
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getAccessToken($code)
    {
        $request = new \XLite\Core\HTTP\Request(static::TOKEN_REQUEST_URL);
        $request->body = array(
            'code'          => $code,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri'  => $this->getRedirectUrl(),
            'grant_type'    => 'authorization_code',
        );

        $response = $request->sendRequest();

        $accessToken = null;
        if (200 == $response->code) {
            $data = json_decode($response->body, true);
            $accessToken = $data['access_token'];
        }

        return $accessToken;
    }

    /**
     * Get OAuth 2.0 client ID
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getClientId()
    {
        return \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_id;
    }

    /**
     * Get OAuth 2.0 client secret
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getClientSecret()
    {
        return \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_secret;
    }
}
