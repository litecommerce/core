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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\Button;

/**
 * 'Print invoice' button widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="page.tabs.after", zone="admin", weight="100")
 */
class PrintInvoice extends \XLite\View\Button\AButton
{
    /**
     * Several inner constants
     */
    const PRINT_INVOICE_JS       = 'button/js/print_invoice.js';
    const PRINT_INVOICE_CSS      = 'button/css/print_invoice.css';
    const PRINT_INVOICE_TEMPLATE = 'button/print_invoice.tpl';

    /**
     * Widget parameters to use
     */
    const PARAM_ORDER_ID  = 'orderId';
    const PARAM_HAS_IMAGE = 'hasImage';


    /**
     * Return list of allowed targets
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.1.0
     */
    public static function getAllowedTargets()
    {
        $targets = parent::getAllowedTargets();
        $targets[] = 'order';

        return $targets;
    }


    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = self::PRINT_INVOICE_JS;

        return $list;
    }

    /**
     * Return CSS files list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = self::PRINT_INVOICE_CSS;

        return $list;
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
        return self::PRINT_INVOICE_TEMPLATE;
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ORDER_ID  => new \XLite\Model\WidgetParam\Int('OrderID', null),
        );
    }

    /**
     * Get default CSS class name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getDefaultStyle()
    {
        return 'button print-invoice';
    }

    /**
     * Get default label 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getDefaultLabel()
    {
        return 'Print invoice';
    }

    /**
     * Get order ID 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getOrderId()
    {
        $orderId = $this->getParam(self::PARAM_ORDER_ID);

        if (empty($orderId)) {
            $orderId = \XLite\Core\Request::getInstance()->order_id;
        }

        return $orderId;
    }

    /**
     * Return URL params to use with onclick event
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getURLParams()
    {
        return array(
            'url_params' => array (
                'target'  => 'order',
                'order_id' => $this->getOrderId(),
                'mode' => 'invoice',
            ),
        );
    }
}
