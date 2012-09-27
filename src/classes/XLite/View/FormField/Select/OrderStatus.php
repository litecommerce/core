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

namespace XLite\View\FormField\Select;

/**
 * Order status selector
 *
 */
class OrderStatus extends \XLite\View\FormField\Select\Regular
{
    /**
     * Common params
     */
    const PARAM_ORDER_ID   = 'orderId';
    const PARAM_ALL_OPTION = 'allOption';

    /**
     * Current order
     *
     * @var \XLite\Model\Order
     */
    protected $order = null;

    /**
     * Inventory warning
     *
     * @var boolean
     */
    protected $inventoryWarning = null;


    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/select_order_status.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/select_order_status.js';

        return $list;
    }


    /**
     * Define widget params
     *
     * @return void
     */
    protected function getOrder()
    {
        if ($this->getParam(self::PARAM_ORDER_ID) && is_null($this->order)) {
            $this->order = \XLite\Core\Database::getRepo('\XLite\Model\Order')
                ->find($this->getParam(self::PARAM_ORDER_ID));
        }

        return $this->order;
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'select_order_status.tpl';
    }

    /**
     * Return default options list
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $list = \XLite\Model\Order::getAllowedStatuses();
        unset($list[\XLite\Model\Order::STATUS_TEMPORARY]);
        unset($list[\XLite\Model\Order::STATUS_INPROGRESS]);

        foreach ($list as $k => $v) {
            $list[$k] = static::t($v);
        }

        return $list;
    }

    protected function getOptions()
    {
        $options = parent::getOptions();

        if (!$this->getOrder() || \XLite\Model\Order::STATUS_AUTHORIZED != $this->getOrder()->getStatus()) {
            unset($options[\XLite\Model\Order::STATUS_AUTHORIZED]);
        }

        return $options;
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ORDER_ID => new \XLite\Model\WidgetParam\Int(
                'Order ID', null, false
            ),
            self::PARAM_ALL_OPTION => new \XLite\Model\WidgetParam\Bool(
                'Show "All" option', false, false
            ),
        );
    }

    /**
     * Flag to show status change warning
     *
     * @return boolean
     */
    protected function isStatusWarning()
    {
        return $this->isInventoryWarning();
    }

    /**
     * Flag to show status change warning
     *
     * @param string $option Option value
     *
     * @return boolean
     */
    protected function isOptionDisabled($option)
    {
        return in_array($option, array(\XLite\Model\Order::STATUS_AUTHORIZED, \XLite\Model\Order::STATUS_PROCESSED, \XLite\Model\Order::STATUS_COMPLETED))
            && $this->isInventoryWarning();
    }

    /**
     * Inventory warning
     *
     * @return boolean
     */
    protected function isInventoryWarning()
    {
        if ($this->getOrder() && !isset($this->inventoryWarning)) {

            foreach ($this->getOrder()->getItems() as $item) {

                if (
                    !isset($this->inventoryWarning)
                    && \XLite\Model\Order::STATUS_QUEUED === $this->getOrder()->getStatus()
                    && $item->getProduct()->getInventory()->getEnabled()
                    && $item->getAmount() > $item->getProduct()->getInventory()->getAmount()
                ) {
                    $this->inventoryWarning = true;
                }

            }

        }

        return $this->inventoryWarning;
    }

    /**
     * Get status warning content
     *
     * @return string
     */
    protected function getStatusWarningContent()
    {
        $content = '';

        if ($this->isInventoryWarning()) {
            $content .= 'Warning! There is not enough product items in stock to process the order';
        }

        return $content;
    }
}
