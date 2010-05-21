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

// FIXME - check this class logic

/**
 * 'Notify me' page
 *
 * @package    XLite
 * @subpackage View
 * @since      3.0
 */
class XLite_Module_ProductAdviser_View_NotifyMe extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('notify_me');

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Notify me when ...';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/ProductAdviser/NotifyMe';
    }

    /**
     * checkAction 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkAction()
    {
        return 'notify_product' == XLite_Core_Request::getInstance()->action;
    }


    /**
     * Check - product is out-of-stock or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOutOfStock()
    {
        return $this->checkAction() && $this->getProduct()->isOutOfStock();
    }

    /**
     * Check - product has small quantity or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSmallQuantity()
    {
        return $this->checkAction() && $this->getProduct()->isInStock();
    }

    /**
     * Check - product has big price or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isBigPrice()
    {
        return $this->checkAction();
    }
}

