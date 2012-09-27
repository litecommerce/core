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

namespace XLite\Module\CDev\Catalog\Drupal;

/**
 * Handler
 *
 * @LC_Dependencies ("CDev\DrupalConnector")
 */
class Module extends \XLite\Module\CDev\DrupalConnector\Drupal\Module implements \XLite\Base\IDecorator
{
    /**
     * Catalog portal to remove 
     * 
     * @var array
     */
    protected $catalogToRemove = array(
        '\XLite\Controller\Customer\OrderList',
        '\XLite\Controller\Customer\Order',
        '\XLite\Controller\Customer\Invoice',
    );

    /**
     * Register a portal
     *
     * @param string  $url        Drupal URL
     * @param string  $controller Controller class name
     * @param string  $title      Portal title OPTIONAL
     * @param integer $type       Node type OPTIONAL
     *
     * @return void
     */
    protected function registerPortal($url, $controller, $title = '', $type = MENU_LOCAL_TASK)
    {
        if (
            !\XLite\Core\Config::getInstance()->CDev->Catalog->disable_checkout
            || !in_array($controller, $this->catalogToRemove)
        ) {
            parent::registerPortal($url, $controller, $title, $type);
        }
    }

}
