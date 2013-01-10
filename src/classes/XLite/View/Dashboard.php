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
 * Dashboard page widget 
 * 
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Dashboard extends \XLite\View\Dialog
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        return array_merge(parent::getAllowedTargets(), array('main'));
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'dashboard';
    }

    /**
     * Add widget specific CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Add widget specific JS-files
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
     * Define tabs
     *
     * @return array
     */
    protected function defineTabs()
    {
        return array(
            'low-inventory' => array(
                'name'   => $this->t('Low inventory products', array('count' => $this->getLowInventoryProductsAmount())),
                'widget' => '\XLite\View\ItemsList\Model\Product\Admin\LowInventoryBlock',
                'style'  => 0 < $this->getLowInventoryProductsAmount() ? 'non-empty' : 'empty',
            ),
            'top_sellers' => array(
                'name'   => '5 top selling products',
                'widget' => '\XLite\View\Product\TopSellersBlock',
                'style'  => 0 < $this->getTopSellersCount() ? 'non-empty' : 'empty',
            ),
        );
    }

    /**
     * Prepare tabs
     *
     * @return array
     */
    protected function getTabs()
    {
        $i = 0;

        $tabs = $this->defineTabs();

        foreach ($tabs as $k => $tab) {
            $tabs[$k]['index'] = $i;
            $tabs[$k]['id']    = sprintf('dashboard-tab-%d', $i);
            $tabs[$k]['class'] = $k;
            $i++;
        }

        return $tabs;
    }

    /**
     * Get tab style (inline)
     *
     * @param array $tab Tab data cell
     *
     * @return string
     */
    protected function getTabStyle(array $tab)
    {
        return $this->isTabActive($tab) ? '' : 'display: none;';
    }

    /**
     * Return true if specified tab is active
     *
     * @param array $tab Tab data cell
     *
     * @return true
     */
    protected function isTabActive(array $tab)
    {
        return 0 === $tab['index'];
    }

    /**
     * Get tab style (CSS classes)
     *
     * @param array $tab Tab data cell
     *
     * @return string
     */
    protected function getTabClass(array $tab)
    {
        $style = !empty($tab['style']) ? $tab['style'] : '';

        return $style . ($this->isTabActive($tab) ? ' active' : '');
    }

    /**
     * Get amount of products in the list
     *
     * @return integer
     */
    protected function getLowInventoryProductsAmount()
    {
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{\XLite\Model\Repo\Product::P_INVENTORY} = \XLite\Model\Repo\Product::INV_LOW;

        return \XLite\Core\Database::getRepo('XLite\Model\Product')->search($cnd, true);
    }

    /**
     * Get count of products in the Top sellers list
     *
     * @return integer
     */
    protected function getTopSellersCount()
    {
        $cnd = new \XLite\Core\CommonCell();
        $cnd->currency = \XLite::getCurrency()->getCurrencyId();

        return \XLite\Core\Database::getRepo('\XLite\Model\OrderItem')->getTopSellers($cnd, true);
    }
}
