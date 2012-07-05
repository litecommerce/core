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
     * Get unique auth provider name to distinguish it from others
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract public function getName();

    /**
     * Get authorization request url
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract public function getAuthRequestUrl();

    /**
     * Concrete implementation must process authorization grant from resource owner
     * 
     * @return array Client information containing at least id and e-mail
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract public function processAuth();

    /**
     * Check if auth provider has all options configured
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract public function isConfigured();

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
     * Get path to small icon to display in header
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getSmallIconPath()
    {
        return '';
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
        return \XLite\Core\Converter::buildFullURL(
            'social_login',
            'login',
            array('auth_provider' => $this->getName())
        );
    }
}
