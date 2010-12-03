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

namespace XLite\Module\CDev\WishList\Controller\Customer;

/**
 * Send to friend product info
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SendFriend extends \XLite\Controller\Customer\Catalog
{
    /**
     * Controller parameters
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'product_id');

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
        if (!$this->getProduct()) {
            $this->setReturnUrl($this->buildURL('main'));

        } elseif (!\XLite\Core\Request::getInstance()->sender_name) {

            \XLite\Core\TopMessage::getInstance()->add(
                '\'Your name\' field is empty',
                \XLite\Core\TopMessage::ERROR
            );
            $this->set('valid', false);

        } elseif (!\XLite\Core\Request::getInstance()->sender_email) {

            \XLite\Core\TopMessage::getInstance()->add(
                '\'Your e-mail\' field is empty',
                \XLite\Core\TopMessage::ERROR
            );
            $this->set('valid', false);

        } elseif (!preg_match('/^' . EMAIL_REGEXP . '$/Ss', \XLite\Core\Request::getInstance()->sender_email)) {

            \XLite\Core\TopMessage::getInstance()->add(
                '\'Your e-mail\' has wrong format',
                \XLite\Core\TopMessage::ERROR
            );
            $this->set('valid', false);

        } elseif (!\XLite\Core\Request::getInstance()->recipient_email) {

            \XLite\Core\TopMessage::getInstance()->add(
                '\'Friend\'s e-mail\' field is empty',
                \XLite\Core\TopMessage::ERROR
            );
            $this->set('valid', false);

        } elseif (!preg_match('/^' . EMAIL_REGEXP . '$/Ss', \XLite\Core\Request::getInstance()->recipient_email)) {

            \XLite\Core\TopMessage::getInstance()->add(
                '\'Friend\'s e-mail\' has wrong format',
                \XLite\Core\TopMessage::ERROR
            );
            $this->set('valid', false);

        } else {

            $mailer = new \XLite\View\Mailer();
            $mailer->sender_name = \XLite\Core\Request::getInstance()->sender_name;
            $mailer->sender_email = \XLite\Core\Request::getInstance()->sender_email;
            $mailer->recipient_email = \XLite\Core\Request::getInstance()->recipient_email;
            $mailer->product = $this->getProduct();
            $mailer->url = $this->getShopUrl(
                $this->buildURL(
                    'product',
                    '',
                    array('product_id' => $this->getProduct()->get('product_id'))
                )
            );
            $mailer->compose(
                \XLite\Core\Request::getInstance()->sender_email,
                \XLite\Core\Request::getInstance()->recipient_email,
                'modules/WishList/send_friend'
            );
            $mailer->send();

            $this->setReturnUrl(
                $this->buildURL(
                    'product',
                    '',
                    array('product_id' => $this->getProduct()->get('product_id'))
                )
            );
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
            isset(\XLite\Core\Request::getInstance()->sender_name)
            && \XLite\Core\Request::getInstance()->sender_name
        ) {
            $result = \XLite\Core\Request::getInstance()->sender_name;

        } elseif ($this->auth->isLogged()) {
            $profile = $this->auth->getProfile();
            $result = $profile->get('billing_name');
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
        $result = '';

        if (isset(\XLite\Core\Request::getInstance()->sender_email)) {
            $result = \XLite\Core\Request::getInstance()->sender_email;

        } elseif ($this->auth->isLogged()) {
            $result = $this->auth->getProfile()->get('login');
        }

        return $result;
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode(
            $this->getProduct()->getName(),
            $this->buildURL('product', '', array('product_id' => $this->getProduct()->getProductId()))
        );
    }

    /**
     * getModelObject
     *
     * @return \XLite\Model\AModel
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
     * @return integer 
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
     * @return \XLite\Model\Category
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
