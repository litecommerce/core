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
 * Main page controller
 *
 */
class Main extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return true;
    }

    /**
     * doActionUpdateInventoryProducts
     *
     * @return void
     */
    protected function doActionUpdateInventoryProducts()
    {
        // Update price and other fields
        \XLite\Core\Database::getRepo('\XLite\Model\Product')
            ->updateInBatchById($this->getPostedData());

        // Update inventory
        \XLite\Core\Database::getRepo('\XLite\Model\Inventory')
            ->updateInBatchById($this->getPostedData());

        \XLite\Core\TopMessage::addInfo(
            'Inventory has been successfully updated'
        );
    }

    protected function doActionHideWelcomeBlock()
    {
        \XLite\Core\Session::getInstance()->hide_welcome_block = 1;

        die('OK');
    }

    protected function doActionHideWelcomeBlockForever()
    {
        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'Internal',
                'name'     => 'hide_welcome_block',
                'value'    => 1,
            )
        );

        die('OK');
    }
}
