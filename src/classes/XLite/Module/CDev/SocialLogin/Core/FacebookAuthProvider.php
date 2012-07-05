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
     * Get unique auth provider name to distinguish it from others
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getName()
    {
        return 'facebook';
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
        return 'https://www.facebook.com/dialog/oauth'
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
            $url = 'https://graph.facebook.com/oauth/access_token'
                . '?client_id=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_id
                . '&redirect_uri=' . urlencode($this->getRedirectUrl())
                . '&client_secret=' . \XLite\Core\Config::getInstance()->CDev->SocialLogin->fb_client_secret
                . '&code=' . urlencode($code);

            $bouncer = new \XLite\Core\HTTP\Request($url);
            $response = $bouncer->sendRequest();

            if (200 == $response->code) {
                parse_str($response->body, $vars);

                $url = 'https://graph.facebook.com/me?access_token=' . urlencode($vars['access_token']);

                $bouncer = new \XLite\Core\HTTP\Request($url);
                $response = $bouncer->sendRequest();

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
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getSmallIconPath()
    {
        return 'modules/CDev/SocialLogin/icons/facebook_small.png';
    }
}
