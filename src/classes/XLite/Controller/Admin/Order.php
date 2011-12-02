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
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Order #' . \XLite\Core\Request::getInstance()->order_id;
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search orders', $this->buildURL('order_list'));
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
        $request = \XLite\Core\Request::getInstance();

        return \XLite\Core\Database::getRepo('\XLite\Model\Order')->updateById(
            \XLite\Core\Request::getInstance()->order_id,
            $this->getRequestData()
        );
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

    // {{{ Tabs

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        return array(
            'default' => 'General info',
        );
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPageTemplates()
    {
        return array(
            'default' => 'order/info.tpl',
        );
    }

    // }}}

}
