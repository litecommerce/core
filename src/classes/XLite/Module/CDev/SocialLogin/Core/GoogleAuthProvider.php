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
     * Get unique auth provider name to distinguish it from others
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getName()
    {
        return 'google';
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
        return 'https://accounts.google.com/o/oauth2/auth'
            . '?client_id=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->gg_client_id
            . '&redirect_uri=' . urlencode($this->getRedirectUrl())
            . '&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email'
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
            $url = 'https://accounts.google.com/o/oauth2/token';

            $request = new \XLite\Core\HTTP\Request($url);
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

                $url = 'https://www.googleapis.com/oauth2/v1/userinfo'
                    . '?access_token=' . $data['access_token'];
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
        return 'modules/CDev/SocialLogin/icons/google_small.png';
    }
}
