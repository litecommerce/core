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
    const PROVIDER_NAME = 'facebook';

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
     * Get unique auth provider name to distinguish it from others
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getName()
    {
        return static::PROVIDER_NAME;
    }

    /**
     * Get authorization request url
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getAuthRequestUrl()
    {
        return static::AUTH_REQUEST_URL
            . '?client_id=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_id
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&scope=' . static::AUTH_REQUEST_SCOPE
            . '&response_type=code';
    }

    /**
     * Concrete implementation must process authorization grant from resource owner
     * 
     * @return array Client information containing at least id and e-mail
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function processAuth()
    {
        $profile = array();

        $code = \XLite\Core\Request::getInstance()->code;

        if (!empty($code)) {
            $request = new \XLite\Core\HTTP\Request(static::TOKEN_REQUEST_URL);
            $request->body = array(
                'code'          => $code,
                'client_id'     => \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_id,
                'client_secret' => \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_secret,
                'redirect_uri'  => $this->getRedirectUrl(),
                'grant_type'    => 'authorization_code',
            );

            $response = $request->sendRequest();

            if (200 == $response->code) {
                $data = json_decode($response->body, true);

                $url = static::PROFILE_REQUEST_URL . '?access_token=' . $data['access_token'];
                $request = new \XLite\Core\HTTP\Request($url);

                $response = $request->sendRequest();

                if (200 == $response->code) {
                    $profile = json_decode($response->body, true);

                    $profile['id'] = $profile['email'];
                }
            }
        }

        return $profile;
    }

    /**
     * Check if auth provider has all options configured
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function isConfigured()
    {
        return !empty(\XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_id)
            && !empty(\XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_secret);
    }

    /**
     * Get path to small icon to display in header
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getSmallIconPath()
    {
        return static::SMALL_ICON_PATH;
    }
}
