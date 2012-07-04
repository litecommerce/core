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

require_once PATH_TESTS . '/Portal/Selenium.php';

abstract class Portal_Page extends Portal_Selenium
{
    /**
     * UI elements: buttons, links, tabs, etc.
     * @var    array Portal_Component
     * @access protected
     * @see    ___func_see___
     * @since  1.1.0
     */ 
    protected $components = array();

    public function __construct()
    {
        parent::__construct();
        $this->getBrowser()->start();
    }
    
    public function __destruct()
    {
        $this->getBrowser()->stop();
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
}
