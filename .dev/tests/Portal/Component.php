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

class Component
{
    /**
     * Identifier
     * @access protected
     * @var    string
     * @see    ___func_see___
     * @since  1.1.0
     */
    protected $id = NULL;
    /**
     * Path of component
     *
     * @var    Selenium\Locator
     * @access protected
     * @see    ___func_see___
     * @since  1.1.0
     */
    protected $locator = NULL;
   
    /**
     * UI elements: buttons, links, tabs, etc.
     * @var    array Portal_Component
     * @access protected
     * @see    ___func_see___
     * @since  1.1.0
     */ 
    protected $components = array();
    
    /**
     * Constructor
     * 
     * @access public
     * @param string $id      component identifier
     * @param Selenium\Locator $locator component locator
     * @see    ___func_see___
     * @since 1.1.0
     */  
    public function __construct($id, $locator)
    {
        $this->id = $id;
        $this->locator = $locator;
        //parent::__construct();
    }
    
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
     * Get component identifier
     * 
     * @access public
     * @return string
     * @see    ___func_see___
     * @since  1.1.0 
     */
    public function getID()
    {
        return $this->id;
    }
    
    /**
     * Get component locator
     * 
     * @access public
     * @return Selenium\Locator
     * @see    ___func_see___
     * @since  1.1.0 
     */
    public function getLocator()
    {
        return $this->locator;
    }
    
    /**
     * Check whether element exists
     * 
     * @access public
     * @return boolean
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function exists()
    {
        return \Portal\Selenium::getBrowser()->isElementPresent($this->locator);
    }
    
    /**
     * Check whether component is visible
     * 
     * @access public
     * @return boolean
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function isVisible()
    {
        return \Portal\Selenium::getBrowser()->isVisible($this->locator);
    }
    
    /**
     * Check whether component is editable
     * 
     * @access public
     * @return boolean
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function isEditable()
    {
        return \Portal\Selenium::getBrowser()->isEditable($this->locator);
    }
    
    /**
     * Check whether element acive
     * Note: It is necessary to ovverride
     * this method to define whether the element
     * is really active or not
     * 
     * @access public
     * @return boolean
     * @see    ___func_see___
     * @since  1.1.0 
     */
    public function isActive()
    {
        return true;
    }
    
    /**
     * Click on element
     * 
     * @access public
     * @return void
     * @see    ___func_see___
     * @since  1.1.0 
     */
    public function click()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->click($this->locator);
        }
    }
    
    /**
     * Double click element
     * 
     * @access public
     * @return void
     * @see    ___func_see___
     * @since  1.1.0  
     */
    public function doubleClick()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->doubleClick($this->locator);
        }
    }
    
    /**
     * Move mouse cursor over element
     * 
     * @access public
     * @return void
     * @see    ___func_see___
     * @since  1.1.0  
     */
    public function mouseOver()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->mouseOver($this->locator);
        }
    }
    
    /**
     * Simulates a user moving the mouse pointer away from the specified element
     * 
     * @access public
     * @return void
     * @see    ___func_see___
     * @since  1.1.0  
     */
    public function mouseOut()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->mouseOut($this->locator);
        }
    }
    
    /**
     * Move the focus to the specified element
     *  
     * @access public
     * @return void
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function focus()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->focus($this->locator);
        }
    }
    
    public function clickAndWait()
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            \Portal\Selenium::getBrowser()->clickAndWait($this->locator, 30 * 1000);
        }
    }
}
