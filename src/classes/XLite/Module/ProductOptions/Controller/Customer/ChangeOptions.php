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
 * Change options from cart / wishlist item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductOptions_Controller_Customer_ChangeOptions extends XLite_Controller_Customer_Abstract
{
    /**
     * Item (cache)
     * 
     * @var    XLite_Model_OrderItem
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $item = null;

    /**
     * Common method to determine current location 
     * 
     * @return array 
     * @access protected 
     * @since  3.0.0
     */      
    protected function getLocation()
    {
        return 'Change options';
    }

    /**
     * Initialize controller
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        if (!$this->getItem()) {
            $this->redirect();
        }
    }

    /**
     * Perform some actions before redirect
     * 
     * @param string $action current action
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function actionPostprocess($action)
    {
        parent::actionPostprocess($action);

        $this->assembleReturnUrl();
    }

    /**
     * Assemble return url 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleReturnUrl()
    {
        $this->set('returnUrl', $this->buildUrl(XLite::TARGET_DEFAULT));
        if (XLite_Core_Request::getInstance()->source == 'cart') {
            $this->set('returnUrl', $this->buildUrl('cart'));
        }
    }

    /**
     * Change product options
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionChange()
    {
        if (XLite_Core_Request::getInstance()->source == 'cart') {
            $this->getItem()->setProductOptions(XLite_Core_Request::getInstance()->product_options);

            $invalidOptions = $this->getItem()->get('invalidOptions');

            if (is_null($invalidOptions)) {
                $this->getCart()->updateItem($this->getItem());
                $this->getItem()->update();
                $this->updateCart();

                XLite_Core_TopMessage::getInstance()->add('Options has been successfully changed');

            } else {

                XLite_Core_TopMessage::getInstance()->add('Invalid options', XLite_Core_TopMessage::ERROR);
                $this->getWidgetParams(self::PARAM_REDIRECT_CODE)->setValue(279);

                $this->set(
                    'returnUrl',
                    $this->buildUrl(
                        'change_options',
                        '',
                        array(
                            'source'     => XLite_Core_Request::getInstance()->source,
                            'storage_id' => XLite_Core_Request::getInstance()->storage_id,
                            'item_id'    => XLite_Core_Request::getInstance()->item_id,
                        )
                    )
                );
            }
        }
    }

    /**
     * Get cart / wishlist item 
     * 
     * @return XLite_Model_OrderItem
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getItem()
    {
        if (is_null($this->item)) {
            $this->item = false;

            if (
                XLite_Core_Request::getInstance()->source == 'cart'
                && is_numeric(XLite_Core_Request::getInstance()->item_id)
            ) {
                $items = $this->getCart()->getItems();

                $itemId = XLite_Core_Request::getInstance()->item_id;
                if (
                    isset($items[$itemId])
                    && $items[$itemId]->getProduct()
                    && $items[$itemId]->hasOptions()
                ) {
                    $this->item = $items[XLite_Core_Request::getInstance()->item_id];
                }
            }
        }

        return $this->item;
    }
}
