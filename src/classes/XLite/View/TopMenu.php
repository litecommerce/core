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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Top menu widget
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class TopMenu extends \XLite\View\AView
{
    /**
     * Array of targets related to the same menu link
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $relatedTargets = array(
        'orders_stats' => array(
            'top_sellers',
        ),
        'product_list' => array(
            'product',
        ),
        'categories' => array(
            'category',
        ),
        'users' => array(
            'profile',
            'address_book',
        ),
        'shipping_methods' => array(
            'shipping_settings',
            'shipping_zones',
            'shipping_rates',
        ),
        'payment_methods' => array(
            'payment_method',
        ),
        'db_backup' => array(
            'db_restore',
            'pack_distr',
        ),
    );


    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/menu.css';

        return $list;
    }


    /**
     * Return widget directory 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'top_menu';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return \XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Returns the list of related targets
     * 
     * @param string $target Target name
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRelatedTargets($target)
    {
        return isset($this->relatedTargets[$target]) 
            ? array_merge(array($target), $this->relatedTargets[$target]) 
            : array($target);
    }
}
