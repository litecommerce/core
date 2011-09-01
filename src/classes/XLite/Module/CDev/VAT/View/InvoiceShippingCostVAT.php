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
 * @since     1.0.8
 */

namespace XLite\Module\CDev\VAT\View;

/**
 * Shipping cost VAT label
 * 
 * @see   ____class_see____
 * @since 1.0.8
 * @ListChild (list="invoice.base.totals.modifier", weight="100")
 */
class InvoiceShippingCostVAT extends \XLite\View\AView
{
    /**
     * Common widget parameter names
     */
    const PARAM_SURCHARGE = 'surcharge';
    const PARAM_TYPE      = 'type';
    const PARAM_ORDER     = 'order';


    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SURCHARGE => new \XLite\Model\WidgetParam\Collection('Surcharge record', array(), false),
            self::PARAM_TYPE      => new \XLite\Model\WidgetParam\String('Surcharge type', '', false),
            self::PARAM_ORDER     => new \XLite\Model\WidgetParam\Object('Order', null, false, '\\XLite\\Model\\Order'),
        );
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
        return 'modules/CDev/VAT/invoice_shipping_cost_vat.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_SURCHARGE)
            && 'shipping' == $this->getParam(self::PARAM_TYPE)
            && $this->getTaxes();
    }

    /**
     * Get including into shipping cost taxes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.8
     */
    protected function getTaxes()
    {
        $list = array();

        foreach ($this->getParam(self::PARAM_ORDER)->getIncludeSurcharges() as $surcharge) {
            if (preg_match('/^CDEV\.VAT\.(\d+).SHIPPING$/Ss', $surcharge->getCode())) {
                $list[] = $surcharge;
            }
        }

        return $list;
    }
}
