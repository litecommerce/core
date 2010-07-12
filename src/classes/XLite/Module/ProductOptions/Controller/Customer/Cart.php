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

namespace XLite\Module\ProductOptions\Controller\Customer;

/**
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Controller\Customer\Cart implements \XLite\Base\IDecorator
{
    /**
     * Get (and create) current cart item
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCurrentItem()
    {
        if (is_null($this->currentItem)) {
            parent::getCurrentItem();

            // set item options if present
            if (
                !is_null($this->getProduct())
                && $this->getProduct()->hasOptions()
            ) {

                $options = array();

                if (isset(\XLite\Core\Request::getInstance()->product_options)) {
                    $options = \XLite\Core\Request::getInstance()->product_options;

                } else {
                    foreach ($this->getProduct()->getDefaultProductOptions() as $class => $oid) {
                        $options[addslashes($class)] = addslashes($oid);
                    }
                }

                $this->currentItem->setProductOptions($options);
            }
        }

        return $this->currentItem;
    }

    /**
     * 'add' action
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_add()
    {
        parent::action_add();

        // check for valid ProductOptions
        if (!is_null($this->getCurrentItem()->get('invalidOptions'))) {

            // got exception (invalid options combination)

            // build invalid options URL
            /* TODO - change to top message
            $io = $this->getCurrentItem()->get('invalidOptions');
            $invalid_options = "";
            foreach ($io as $i => $o) {
                $invalid_options .= "&" . urlencode("invalid_options[$i]") . "=" . urlencode($o);
            }
            */

            // delete item from cart and switch back to product details
            $key = $this->getCurrentItem()->get('key');
            foreach ($this->getCart()->getIitems() as $i) {
                if ($i->get('key') == $key) {
                    $this->cart->deleteItem($i);
                    break;
                }
            }
            $this->updateCart();

            // TODO - add top message

            $this->set('returnUrl', $this->buildUrl('product', '', array('product_id' => \XLite\Core\Request::getInstance()->product_id)));
        }
    }
}

