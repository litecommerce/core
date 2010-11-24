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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\FormField\Select;

/**
 * \XLite\View\FormField\Select\AccessLevel 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AccessLevel extends \XLite\View\FormField\Select\Regular
{
    /**
     * Determines if this field is visible for customers or not 
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $isAllowedForCustomer = false;


    /**
     * getDefaultOptions
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultOptions()
    {
        return \XLite\Core\Auth::getInstance()->getUserTypesRaw();
    }

    /**
     * Check field value validity
     *
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function checkFieldValue()
    {
        return in_array($this->getValue(), \XLite\Core\Auth::getInstance()->getAccessLevelsList());
    }
}

