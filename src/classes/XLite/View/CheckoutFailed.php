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

namespace XLite\View;

/**
 * Checkout failed page
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center")
 */
class CheckoutFailed extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'checkoutFailed';

        return $list;
    }


    /**
     * Get continue URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getContinueURL()
    {
        $url = \XLite\Core\Session::getInstance()->continueURL;

        if (!$url && isset($_SERVER['HTTP_REFERER'])) {

            $url = $_SERVER['HTTP_REFERER'];
        }

        if (!$url) {

            $url = $this->buildURL('main');
        }

        return $url;
    }

    /**
     * Get Re-order URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getReorderURL()
    {
        return $this->buildURL('cart', 'add_order', array('order_id' => \XLite\Core\Request::getInstance()->order_id));
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'checkout/failed.tpl';
    }
}
