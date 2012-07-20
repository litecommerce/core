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

namespace XLite\Controller\Admin;

/**
 * Order page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Order extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Controller parameters
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.11
     */
    protected $params = array('target', 'order_id', 'page');

    /**
     * Check ACL permissions
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage orders');
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkAccess()
    {
        return parent::checkAccess()
            && \XLite\Core\Request::getInstance()->order_id
            && \XLite\Core\Database::getRepo('XLite\Model\Order')->find(\XLite\Core\Request::getInstance()->order_id);
    }

    /**
     * getRequestData
     * TODO: to remove
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRequestData()
    {
        return \Includes\Utils\ArrayManager::filterByKeys(
            \XLite\Core\Request::getInstance()->getData(),
            array('status', 'notes')
        );
    }

    /**
     * doActionUpdate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        $data = $this->getRequestData();
        $orderId = \XLite\Core\Request::getInstance()->order_id;

        $changes = $this->getOrderChanges($orderId, $data);

        \XLite\Core\Database::getRepo('\XLite\Model\Order')->updateById(
            $orderId,
            $data
        );

        \XLite\Core\OrderHistory::getInstance()->registerOrderChanges($orderId, $changes);
    }

    /**
     * Return requested changes for the order
     *
     * @param integer $orderId Order identificator
     * @param array   $data    Data to change
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOrderChanges($orderId, array $data)
    {
        $changes = array();
        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($orderId);

        foreach ($data as $name => $value) {

            $orderValue = $order->{'get' . ucfirst($name)}();

            if ($orderValue !== $value) {

                $changes[$name] = array(
                    'old' => $orderValue,
                    'new' => $value,
                );
            }
        }

        return $changes;
    }

    /**
     * getViewerTemplate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getViewerTemplate()
    {
        $result = parent::getViewerTemplate();

        if ('invoice' === \XLite\Core\Request::getInstance()->mode) {
            $result = 'common/print_invoice.tpl';
        }

        return $result;
    }

    // {{{ Pages

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        $list = parent::getPages();
        $list['default'] = 'General info';
        $list['history'] = 'History';

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageTemplates()
    {
        $list = parent::getPageTemplates();
        $list['default'] = 'order/info.tpl';
        $list['history'] = 'order/history.tpl';

        return $list;
    }

    // }}}
}
