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
 * Auth provider abstract class
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
abstract class AAuthProvider extends \XLite\Base\Singleton
{
    /**
     * Authorization grant provider param name
     */
    const AUTH_PROVIDER_PARAM_NAME = 'auth_provider';

    /**
     * State parameter is used to maintain state between the request and callback
     */
    const STATE_PARAM_NAME = 'state';

    /**
     * Get OAuth 2.0 client ID
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    abstract protected function getClientId();

    /**
     * Get OAuth 2.0 client secret
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    abstract protected function getClientSecret();

    /**
     * Get authorization request url
     * 
     * @param string $state State parameter to include in request
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getAuthRequestUrl($state)
    {
        return static::AUTH_REQUEST_URL
            . '?client_id=' . $this->getClientId()
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&scope=' . static::AUTH_REQUEST_SCOPE
            . '&response_type=code'
            . '&' . static::STATE_PARAM_NAME . '=' . urlencode($state);
    }

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
     * Check if current request belongs to the concrete implementation of auth provider
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function detectAuth()
    {
        return \XLite\Core\Request::getInstance()->{static::AUTH_PROVIDER_PARAM_NAME} == $this->getName();
    }

    /**
     * Process authorization grant and return array with profile data
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
        return $this->getClientId() && $this->getClientSecret();
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
            . '?client_id=' . $this->getClientId()
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&client_secret=' . $this->getClientSecret()
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
     * Get authorization grant redirect url
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getRedirectUrl()
    {
        return \Includes\Utils\URLManager::getShopURL(
            \XLite\Core\Converter::buildURL(
                'social_login',
                'login',
                array('auth_provider' => $this->getName())
            ),
            \XLite\Core\Request::getInstance()->isHTTPS(),
            array(),
            null,
            false
        );
    }
}
