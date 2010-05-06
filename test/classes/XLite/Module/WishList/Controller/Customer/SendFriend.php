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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Send to friend product info
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WishList_Controller_Customer_SendFriend extends XLite_Controller_Customer_Catalog
{
    /**
     * Get page title 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Tell a friend';
    }

    /**
     * Send info
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSendFriend()
    {
        if ($this->getProduct()) {
            $mailer = new XLite_Model_Mailer();
            $mailer->sender_name  = $this->getSenderName();
            $mailer->sender_email = $this->getSenderEmail();
            $mailer->recipient_email = XLite_Core_Request::getInstance()->recipient_email;
            $mailer->product = $this->getProduct();
            $mailer->url = $this->buildURL(
                'product',
                '',
                array('product_id' => $this->getProduct()->get('product_id'))
            );
            $mailer->compose(
                $this->getSenderEmail,
                XLite_Core_Request::getInstance()->recipient_email,
                'modules/WishList/send_friend'
            );
            $mailer->send();

            $this->setReturnUrl(
                $this->buildURL('product', '', array('product_id' => $this->getProduct()->get('product_id')))
            );

        } else {
            $this->setReturnUrl($this->buildURL('main'));
        }
    }

    /**
     * Get sender name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSenderName()
    {
        $result = '';

        if (
            isset(XLite_Core_Request::getInstance()->sender_name)
            && XLite_Core_Request::getInstance()->sender_name
        ) {
            $result = XLite_Core_Request::getInstance()->sender_name;

        } elseif ($this->auth->isLogged()) {
            $profile = $this->auth->getProfile();
            $resut = $profile->get('billing_firstname') . ' ' . $profile->get('billing_lastname');
        }

        return $result;
    }

    /**
     * Get sender email 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSenderEmail()
    {
        return isset(XLite_Core_Request::getInstance()->sender_email)
            ? XLite_Core_Request::getInstance()->sender_email
            : $this->auth->getComplex('profile.login');
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation($includeCurrent = false)
    {
        parent::addBaseLocation(true);

        $this->locationPath->addNode(
            new XLite_Model_Location(
                $this->getProduct()->get('name'),
                $this->buildURL('product', '', array('product_id' => $this->getProduct()->get('product_id')))
            )
        );
    }

    /**
     * getModelObject
     *
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getProduct();
    }

    /**
     * Get product category id
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if (!$categoryId) {
            $productCategory = $this->getProductCategory();
            if ($productCategory) {
                $categoryId = $productCategory->get('category_id');
            }
        }

        return $categoryId;
    }

    /**
     * Return random product category 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @since  3.0.0
     */
    protected function getProductCategory()
    {
        $list = $this->getProduct()->getCategories();

        return array_shift($list);
    }

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

}
