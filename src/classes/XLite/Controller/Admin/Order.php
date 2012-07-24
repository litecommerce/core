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
     * handleRequest
     *
     * @return void
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function handleRequest()
    {
        if (
            !empty(\XLite\Core\Request::getInstance()->action)
            && 'update' != \XLite\Core\Request::getInstance()->action
        ) {
            $order = $this->getOrder();

            if (isset($order)) {

                $allowedTransactions = $order->getAllowedPaymentActions();

                if (isset($allowedTransactions[\XLite\Core\Request::getInstance()->action])) {
                    \XLite\Core\Request::getInstance()->transactionType = \XLite\Core\Request::getInstance()->action;
                    \XLite\Core\Request::getInstance()->action = 'PaymentTransaction';
                    \XLite\Core\Request::getInstance()->setRequestMethod('POST');
                }
            }
        }

        return parent::handleRequest();
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
            && $this->getOrder();
    }

    /**
     * Get order
     *
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getOrder()
    {
        $id = intval(\XLite\Core\Request::getInstance()->order_id);

        return $id ? \XLite\Core\Database::getRepo('XLite\Model\Order')->find($id) : null;
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
            array('status', 'adminNotes')
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
     * doActionUpdate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionPaymentTransaction()
    {
        $request = \XLite\Core\Request::getInstance();

        $order = \XLite\Core\Database::getRepo('\XLite\Model\Order')->find($request->order_id);

        if ($order) {
            $transactions = $order->getPaymentTransactions();
            if (!empty($transactions)) {
                $transactions[0]->getPaymentMethod()->getProcessor()->doTransaction(
                    $transactions[0],
                    $request->transactionType
                );
            }
        }

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
        $list['invoice'] = 'Invoice';
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

        $list['default'] = 'order/page/info.tab.tpl';
        $list['invoice'] = 'order/page/invoice.tpl';
        $list['history'] = 'order/history.tpl';

        return $list;
    }

    // }}}
}
