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
 * Invoice widget
 *
 *
 * @ListChild (list="order.children", weight="30")
 */
class Invoice extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ORDER = 'order';

    /**
     * Shipping modifier (cache)
     *
     * @var \XLite\Model\Order\Modifier
     */
    protected $shippingModifier;


    /**
     * Get order
     *
     * @return \XLite\Model\Order
     */
    public function getOrder()
    {
        return $this->getParam(self::PARAM_ORDER);
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'order/invoice/style.css';

        return $list;
    }


    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ORDER => new \XLite\Model\WidgetParam\Object(
                'Order', null, false, '\XLite\Model\Order'
            ),
        );
    }

    /**
     * Return default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'order/invoice/body.tpl';
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getOrder();
    }

    /**
     * Get shipping modifier
     *
     * @return \XLite\Model\Order\Modifier
     */
    protected function getShippingModifier()
    {
        if (!isset($this->shippingModifier)) {
            $this->shippingModifier = $this->getOrder()
                ->getModifier(\XLite\Model\Base\Surcharge::TYPE_SHIPPING, 'SHIPPING');
        }

        return $this->shippingModifier;
    }

    /**
     * Get item fescription block columns count 
     * 
     * @return integer
     */
    protected function getItemDescriptionCount()
    {
        return 3;
    }

    /**
     * Get columns span 
     *
     * @return integer
     */
    protected function getColumnsSpan()
    {
        return 4 + count($this->getOrder()->getItemsExcludeSurcharges());
    }

    /**
     * Get payment methods with instructions 
     * 
     * @return array
     */
    protected function getPaymentInstructions()
    {
        $list = array();

        foreach ($this->getOrder()->getVisiblePaymentMethods() as $method) {
            if ($method->getInstruction()) {
                $list[] = $method;
            }
        }

        return $list;
    }

    // {{{ Surcharges

    /**
     * Get surcharge totals
     *
     * @return array
     */
    protected function getSurchargeTotals()
    {
        return $this->getOrder()->getSurchargeTotals();
    }

    /**
     * Get surcharge class name
     *
     * @param string $type      Surcharge type
     * @param array  $surcharge Surcharge
     *
     * @return string
     */
    protected function getSurchargeClassName($type, array $surcharge)
    {
        return 'order-modifier '
            . $type . '-modifier '
            . strtolower($surcharge['code']) . '-code-modifier';
    }

    /**
     * Format surcharge value
     *
     * @param array $surcharge Surcharge
     *
     * @return string
     */
    protected function formatSurcharge(array $surcharge)
    {
        return $this->formatPrice(abs($surcharge['cost']), $this->getOrder()->getCurrency());
    }

    // }}}

}
