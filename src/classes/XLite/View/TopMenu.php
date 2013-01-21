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
 * Top menu widget
 *
 */
class TopMenu extends \XLite\View\AView
{
    /**
     * Array of targets related to the same menu link
     *
     * @var array
     */
    protected $relatedTargets = array(
        'orders_stats' => array(
            'top_sellers',
        ),
        'order_list' => array(
            'order',
        ),
        'product_list' => array(
            'product',
        ),
        'categories' => array(
            'category',
        ),
        'profile_list' => array(
            'profile',
            'address_book',
        ),
        'shipping_methods' => array(
            'shipping_settings',
            'shipping_zones',
            'shipping_rates',
        ),
        'payment_settings' => array(
            'payment_method',
            'payment_appearance',
        ),
        'db_backup' => array(
            'db_restore',
        ),
        'product_classes' => array(
            'product_class',
            'attributes',
        ),
    );


    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/menu.css';

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

        $list[] = $this->getDir() . '/controller.js';

        return $list;
    }


    /**
     * Return widget directory
     *
     * @return string
     */
    protected function getDir()
    {
        return 'top_menu';
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Returns the list of related targets
     *
     * @param string $target Target name
     *
     * @return array
     */
    protected function getRelatedTargets($target)
    {
        return isset($this->relatedTargets[$target])
            ? array_merge(array($target), $this->relatedTargets[$target])
            : array($target);
    }
}
