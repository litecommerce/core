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
 * @package    XLite
 * @subpackage Validator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract validator
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Validator_AValidator extends XLite_View_AView
{
    /**
     * Return widget default template
     * FIXME - backward compatibility
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->template;
    }

    /**
     * Attempts to display widget using its template 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function display()
    {
        if ($this->isVisible()) {
            $class = strtolower(get_class($this));
     		echo '<input type="hidden" name="VALIDATE[' . $class . '][' . $this->get('field') . ']" value="1" />' . "\n";
        }

        if (!$this->isValid()) {
            parent::display();
        }
    }

    public function isValidationUnnecessary()
    {
        $class = strtolower(get_class($this));

        return !isset(XLite_Core_Request::getInstance()->VALIDATE)
            || !isset(XLite_Core_Request::getInstance()->VALIDATE[$class])
            || !isset(XLite_Core_Request::getInstance()->VALIDATE[$class][$this->get('field')]);
    }

    /**
     * Check validation status 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isValid()
    {
        return true;
    }
}
