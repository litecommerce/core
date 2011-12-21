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

namespace XLite\View;


/**
 * Order status
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class OrderStatus extends \XLite\View\AView
{
    /**
     * Widget parameter. Order.
     */
    const PARAM_ORDER       = 'order';

    /**
     * Widget parameter. Use wrapper flag.
     */
    const PARAM_USE_WRAPPER = 'useWrapper';


    /**
     * CSS classes associations.
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $cssClasses = array(
        \XLite\Model\Order::STATUS_TEMPORARY  => 'order-status-temporary',
        \XLite\Model\Order::STATUS_INPROGRESS => 'order-status-inprogress',
        \XLite\Model\Order::STATUS_QUEUED     => 'order-status-queued',
        \XLite\Model\Order::STATUS_PROCESSED  => 'order-status-processed',
        \XLite\Model\Order::STATUS_COMPLETED  => 'order-status-completed',
        \XLite\Model\Order::STATUS_FAILED     => 'order-status-failed',
        \XLite\Model\Order::STATUS_DECLINED   => 'order-status-declined',
    );

    /**
     * Titles associations.
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $titles = array(
        \XLite\Model\Order::STATUS_TEMPORARY  => 'Temporary',
        \XLite\Model\Order::STATUS_INPROGRESS => 'In progress',
        \XLite\Model\Order::STATUS_QUEUED     => 'Queued',
        \XLite\Model\Order::STATUS_PROCESSED  => 'Processed',
        \XLite\Model\Order::STATUS_COMPLETED  => 'Completed',
        \XLite\Model\Order::STATUS_FAILED     => 'Failed',
        \XLite\Model\Order::STATUS_DECLINED   => 'Declined',
    );


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/order_status.tpl';
    }

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
            self::PARAM_ORDER       => new \XLite\Model\WidgetParam\Object('Order', null, false, '\XLite\Model\Order'),
            self::PARAM_USE_WRAPPER => new \XLite\Model\WidgetParam\Bool('Use wrapper', false),
        );
    }

    /**
     * Return CSS class to use with wrapper
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCSSClass()
    {
        return $this->cssClasses[$this->getParam(self::PARAM_ORDER)->getStatus()] ?: '';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTitle()
    {
        return $this->titles[$this->getParam(self::PARAM_ORDER)->getStatus()] ?: '';
    }
}
