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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\XPaymentsConnector\Core;

/**
 * XPayments core
 *
 * @see   ____class_see____
 * @since 1.0.19
 */
class XPayments extends \XLite\Base\Singleton
{
    const REQ_CURL    = 1;
    const REQ_OPENSSL = 2;
    const REQ_DOM     = 4;

    /**
     * Required fields
     *
     * @var  array() 
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $requiredFields = array(
        'store_id',
        'url',
        'public_key',
        'private_key',
        'private_key_password',
    );

    /**
     * Map fields
     *
     * @var  array()
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $mapFields = array(
        'store_id'              => 'xpc_shopping_cart_id',
        'url'                   => 'xpc_xpayments_url',
        'public_key'            => 'xpc_public_key',
        'private_key'           => 'xpc_private_key',
        'private_key_password'  => 'xpc_private_key_password',
    );

    /**
     * Get configuration array from configuration deployement path
     *
     * @param string $configuration configuration string
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getConfiguration($configuration)
    {
        return unserialize(base64_decode($configuration));
    }
    
    /**
     * Check if the deploy configuration is correct array
     *
     * @param array $configuration configuration array
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkDeployConfiguration($configuration)
    {
        return is_array($configuration)
            && ($this->requiredFields === array_intersect(array_keys($configuration), $this->requiredFields));
    }
    
    /**
     * Store configuration array into DB
     *
     * @param array $configuration configuration array
     *
     * @return void 
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setConfiguration($configuration)
    {
        foreach ($this->mapFields as $origName => $dbName) {
            $setting = \XLite\Core\Database::getRepo('XLite\Model\Config')
                ->findOneBy(array('name' => $dbName, 'category' => 'CDev\XPaymentsConnector'));

            \XLite\Core\Database::getRepo('XLite\Model\Config')->update(
                $setting,
                array('value' => $configuration[$origName])
            );
        }
    }

   /**
     * Check - module is configured or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isConfigured()
    {
        $failed = false;

        // Check shopping cart id
        $failed |= empty(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_shopping_cart_id)
            || !preg_match('/^[\da-f]{32}$/Ss', \XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_shopping_cart_id);

        // Check URL
        $failed |= empty(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_xpayments_url);

        $parsed_url = @parse_url(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_xpayments_url);

        $failed |= !$parsed_url || !isset($parsed_url['scheme']) || $parsed_url['scheme'] != 'https';

        // Check public key
        $failed |= empty(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_public_key);

        // Check private key
        $failed |= empty(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_private_key);

        // Check private key password
        $failed |= empty(\XLite\Core\Config::getInstance()->XPaymentsConnector->xpc_private_key_password);

        return !$failed;
    }

    /**
     * Check module requirements
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function checkRequirements()
    {
        $code = 0;

        if (!function_exists('curl_init')) {
            $code = $code | static::REQ_CURL;
        }

        if (
            !function_exists('openssl_pkey_get_public') || !function_exists('openssl_public_encrypt')
            || !function_exists('openssl_get_privatekey') || !function_exists('openssl_private_decrypt')
            || !function_exists('openssl_free_key')
        ) {
            $code = $code | static::REQ_OPENSSL;
        }

        if (!class_exists('DOMDocument')) {
            $code = $code | static::REQ_DOM;
        }

        return $code;
    }

}
