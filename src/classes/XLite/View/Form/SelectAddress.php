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

namespace XLite\View\Form;

/**
 * Select address form
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SelectAddress extends \XLite\View\Form\AForm
{
    /**
     * Current form name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return 'select_address';
    }

    /**
     * getDefaultTarget 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTarget()
    {
        return 'select_address';
    }

    /**
     * getDefaultAction 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'select';
    }

    /**
     * Return list of the form default parameters
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultParams()
    {
        $list = parent::getDefaultParams();

        $list['atype'] = \XLite\Core\Request::getInstance()->atype;
        $list['addressId'] = $this->getCurrentAddressId();

        return $list;
    }
}
