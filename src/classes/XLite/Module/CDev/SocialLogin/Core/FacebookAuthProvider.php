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
 * Facebook auth provider
 * 
 * @see   ____class_see____
 * @since 1.0.24
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
            . '?client_id=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_id
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&scope=email'
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
            $accessToken = $this->getAccessToken($code);
            
            if ($accessToken) {
                $request = new \XLite\Core\HTTP\Request($this->getProfileRequestUrl($accessToken));
                $response = $request->sendRequest();

                if (200 == $response->code) {
                    $profile = json_decode($response->body, true);
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
        return !empty(\XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_id)
            && !empty(\XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_secret);
    }

    /**
     * Get path to small icon to display in header
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getSmallIconPath()
    {
        return static::SMALL_ICON_PATH;
    }

    /**
     * Get url to request access token
     * 
     * @param string $code Authorization code
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getTokenRequestUrl($code)
    {
        return static::TOKEN_REQUEST_URL
            . '?client_id=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_id
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&client_secret=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_secret
            . '&code=' . urlencode($code);
    }

    /**
     * Get url used to access user profile info
     * 
     * @param string $accessToken Access token
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getProfileRequestUrl($accessToken)
    {
        return static::PROFILE_REQUEST_URL . '?access_token=' . urlencode($accessToken);
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
        $request = new \XLite\Core\HTTP\Request($this->getTokenRequestUrl($code));
        $response = $request->sendRequest();

        $accessToken = null;

        if (200 == $response->code) {
            parse_str($response->body, $data);
            $accessToken = $data['access_token'];
        }

        return $accessToken;
    }
}
