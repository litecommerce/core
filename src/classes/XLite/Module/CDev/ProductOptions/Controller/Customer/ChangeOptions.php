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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\ProductOptions\Controller\Customer;

/**
 * Change options from cart / wishlist item
 *
 */
class ChangeOptions extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Item (cache)
     *
     * @var \XLite\Model\OrderItem
     */
    protected $item = null;

    /**
     * Internal error flag
     *
     * @var boolean
     */
    protected $internalError = false;

    /**
     * Get page title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('"X product" options', array('product' => $this->getItem()->getName()));
    }

    /**
     * Initialize controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        if (!$this->getItem()) {
            $this->redirect();
        }
    }

    /**
     * Get cart / wishlist item
     *
     * @return \XLite\Model\OrderItem
     */
    public function getItem()
    {
        if (!isset($this->item)) {
            $this->item = false;

            if (
                \XLite\Core\Request::getInstance()->source == 'cart'
                && is_numeric(\XLite\Core\Request::getInstance()->item_id)
            ) {
                $item = $this->getCart()->getItemByItemId(\XLite\Core\Request::getInstance()->item_id);

                if (
                    $item
                    && $item->getProduct()
                    && $item->hasOptions()
                ) {
                    $this->item = $item;
                }
            }
        }

        return $this->item;
    }

    /**
     * Get product
     *
     * @return \XLite\Model\Product
     */
    public function getProduct()
    {
        return $this->getItem()->getProduct();
    }


    /**
     * Common method to determine current location
     *
     * @return array
     */
    protected function getLocation()
    {
        return $this->getTitle();
    }

    /**
     * Perform some actions before redirect
     *
     * FIXME: check. Action should not be an optional param
     *
     * @param string $action Current action OPTIONAL
     *
     * @return void
     */
    protected function actionPostprocess($action = null)
    {
        parent::actionPostprocess($action);

        if ($action) {
            $this->assembleReturnURL();
        }
    }

    /**
     * Assemble return url
     *
     * @return void
     */
    protected function assembleReturnURL()
    {
        $this->setReturnURL($this->buildURL(\XLite::TARGET_DEFAULT));

        if ($this->internalError) {
            $this->setReturnURL(
                $this->buildURL(
                    'change_options',
                    '',
                    array(
                        'source' => \XLite\Core\Request::getInstance()->source,
                        'storage_id' => \XLite\Core\Request::getInstance()->storage_id,
                        'item_id' => \XLite\Core\Request::getInstance()->item_id,
                    )
                )
            );
        } elseif (\XLite\Core\Request::getInstance()->source == 'cart') {
            $this->setReturnURL($this->buildURL('cart'));
        }
    }

    /**
     * Change product options
     *
     * @return void
     */
    protected function doActionChange()
    {
        $this->internalError = false;

        if ('cart' == \XLite\Core\Request::getInstance()->source) {

            $options = $this->getItem()
                ->getProduct()
                ->prepareOptions(\XLite\Core\Request::getInstance()->product_options);

            if (
                is_array($options)
                && $this->getItem()->getProduct()->checkOptionsException($options)
            ) {
                $this->getItem()->setProductOptions($options);
                $this->updateCart();

                \XLite\Core\TopMessage::addInfo('Options have been successfully changed');

                $this->setSilenceClose();

            } else {
                \XLite\Core\TopMessage::addError(
                    'The product options you have selected are not valid or fall into an exception.'
                    . ' Please select other product options'
                );

                $this->setInternalRedirect();
                $this->internalError = true;
            }
        }
    }
}
