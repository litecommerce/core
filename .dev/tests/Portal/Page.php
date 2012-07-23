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
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Portal
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.1.0
 */

namespace Portal;

require_once PATH_TESTS . '/Portal/Autoload.php';

abstract class Page
{
    /**
     * UI elements: buttons, links, tabs, etc.
     * @var    array Portal_Component
     * @access protected
     * @see    ___func_see___
     * @since  1.1.0
     */ 
    protected $components = array();

    /**
     *Configuration
     * 
     * @access protected
     * @var    array
     * @see    ___func_see___
     * @since  1.1.0 
     */
    protected $testConfig = NULL;

    public function __construct()
    {
        \Portal\Selenium::start();
    }
    
    /**
     * Perform all necessary actions to open this page:
     * press the special button, click menu item or just
     * enter the page URL into web-browser address bar
     *
     * @access public
     * @see    ___func_see___
     * @since  1.1.0
     */
    abstract public function open();

    /**
     * Get component
     * 
     * @access public
     * @param string $componentID component identifier 
     * @return Portal_Component
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function __get($componentID)
    {
        $res = false;

        foreach ($this->components as $comp) {
            if ($comp->getID() === $componentID) {
                $res = $comp; 
            }
        }
        
        return $res;
    }
    /**
     * Get options from ini-file
     *
     * @return array
     * @since  1.0.0
     */
    protected function getConfig()
    {
        if (is_null($this->testConfig)) {
            $configFile = XLITE_DEV_CONFIG_DIR . LC_DS . 'xlite-test.config.php';

            if (file_exists($configFile) && false !== ($config = parse_ini_file($configFile, true))) {
                return $config;

            } else {
                die('Config file not found: ' . $configFile);
            }
        }
        
        return $this->testConfig;
    }
}
