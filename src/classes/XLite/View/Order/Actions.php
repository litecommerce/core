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

namespace XLite\View\Order;

/**
 * Actions  row
 * 
 *
 * @ListChild (list="order.actions", weight="200", zone="admin")
 */
class Actions extends \XLite\View\AView
{
    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'order/page/parts/action.buttons.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && 0 < count($this->defineOrderActions());
    }

    /**
     * Get order aActions
     *
     * @param \XLite\Model\Order $entity Order
     *
     * @return array
     */
    protected function getOrderActions(\XLite\Model\Order $entity)
    {
        $list = array();

        foreach ($this->defineOrderActions($entity) as $action) {
            $parameters = array(
                'label' => ucfirst($action),
                'location' => \XLite\Core\Converter::buildURL(
                    'order',
                    $action,
                    array('order_id' => $this->getOrder()->getOrderId())
                ),
            );
            $list[] = $this->getWidget($parameters, 'XLite\View\Button\Link');
        }

        return $list;
    }

    /**
     * Define order actions
     *
     * @return array
     */
    protected function defineOrderActions()
    {
        return $this->getOrder()->getAllowedActions();
    }
}

