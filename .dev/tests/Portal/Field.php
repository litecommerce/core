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

class Field extends \Portal\Component
{
    /**
     * Indicates whether this field is mandaroty or not
     * 
     * @var    boolean
     * @access protected
     * @see    ___func_see___
     * @since  1.1.0
     */
    protected $mandatory;
    
    /**
     * Constructor
     * 
     * @access public
     * @param string $id                component identifier
     * @param Selenium\Locator $locator component locator
     * @param boolean $mandatory        indicates whether field is mandatory
     * @see    ___func_see___
     * @since 1.1.0
     */  
    public function __construct($id, $locator, $mandatory = false)
    {
        parent::__construct($id, $locator);
        $this->mandatory = $mandatory;
    }
    
    /**
     * True if this field is mandatory
     * 
     * @return boolean
     * @access public
     * @see    ___func_se___
     * @since  1.1.0 
     */
    public function isMandatory()
    {
        return $this->mandatory;
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
        return parent::isActive() && \Portal\Selenium::getBrowser()->isEditable($this->locator);
    }
    
    /**
     * Enter text into input field or textarea
     *  
     * @access public
     * @param  string $text Entered text
     * @return void
     * @see    ___func_see___
     * @since  1.1.0
     */
    public function enter($text)
    {
        if (
            $this->exists()
            && $this->isVisible()
            && $this->isActive()
        ) {
            if (!empty($text)){
                $type = substr($text, 0, -1);
                $typeKeys = substr($text, -1);
            }
            else{
                $type = '';
                $typeKeys = '';
            }
            \Portal\Selenium::getBrowser()->type($this->locator, '');
            \Portal\Selenium::getBrowser()->type($this->locator, $text);
            $this->focus();
            \Portal\Selenium::getBrowser()->typeKeys($this->locator, $typeKeys);
        }
    }
}
