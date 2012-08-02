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

namespace XLite\Module\CDev\DrupalConnector\Core;

/**
 * Caller 
 * 
 */
class Caller extends \XLite\base\Singleton
{
    /**
     * Initialized flag
     * 
     * @var boolean
     */
    protected $initialized;

    // {{{ Initialization

    /**
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
        if (
            !defined('DRUPAL_ROOT')
            && \XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_path
            && !isset($this->initialized)
        ) {
            if (file_exists(\XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_path . '/includes/bootstrap.inc')) {

                define('DRUPAL_ROOT', \XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_path);
                require_once (DRUPAL_ROOT . '/includes/bootstrap.inc');
                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
                $this->initialized = true;

            } else {
                $this->initialized = false;
            }

        } elseif (defined('DRUPAL_ROOT')) {
            $this->initialized = true;
        }
    }

    // }}}

    // {{{ Interface

    /**
     * Check - caller initialized or not
     * 
     * @return boolean
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * Function caller
     * 
     * @param string $name      Function name
     * @param array  $arguments Function arguments OPTIONAL
     *  
     * @return mixed
     */
    public function __call($name, array $arguments = array())
    {
        return $this->initialized ? call_user_func_array($name, $arguments) : null;
    }

    // }}}
}
