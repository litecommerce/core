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
 * Orders list controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class RecentOrders extends \XLite\Controller\Admin\OrderList
{
    /**
     * Handles the request.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        if (!isset(\XLite\Core\Request::getInstance()->mode)) {
            \XLite\Core\Request::getInstance()->{self::PARAM_ACTION} = 'search';
        }

        parent::handleRequest();
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Recent orders';
    }

    /**
     * doActionSearch
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSearch()
    {
        parent::doActionSearch();

        \XLite\Core\Session::getInstance()
            ->{\XLite\View\ItemsList\Order\Admin\Recent::getSessionCellName()} = array(
                \XLite\Model\Repo\Order::P_DATE => array(LC_START_TIME - 86400, LC_START_TIME),
            );

        $this->setReturnURL($this->buildURL('recent_orders', '', array('mode' => 'search')));
    }
}
