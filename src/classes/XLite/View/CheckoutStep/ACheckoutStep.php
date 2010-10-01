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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\CheckoutStep;

/**
 * \XLite\View\CheckoutStep\ACheckoutStep 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ACheckoutStep extends \XLite\View\Dialog
{
    /**
     * Return step templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getStepDir();

    /**
     * Return top message text for error
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getErrorText();

    /**
     * Return top message text for success
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getSuccessText();


    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'checkout/steps/' . $this->getStepDir();
    }


    /**
     * Determines if this step is so called "regular", or a "pseudo" one
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public static function isRegularStep()
    {
        return true;
    }


    /**
     * Return step top message
     *
     * @param bool $isPassed flag; is step passed or not
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getTopMessage($isPassed)
    {
        return array(
            \XLite\Core\TopMessage::FIELD_TYPE => $isPassed ? \XLite\Core\TopMessage::INFO : \XLite\Core\TopMessage::ERROR,
            \XLite\Core\TopMessage::FIELD_TEXT => $isPassed ? $this->getSuccessText() : $this->getErrorText(),
        );
    }
}
