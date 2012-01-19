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

namespace XLite\View\Product\Details\Customer\Page;

/**
 * APage
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class APage extends \XLite\View\Product\Details\Customer\ACustomer
{
    /**
     * Tabs (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $tabs;

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = self::getDir() . '/controller.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();

        $list['js'][] = 'js/jquery.blockUI.js';

        return $list;
    }

    /**
     * Check - 'items available' label is visible or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isAvailableLabelVisible()
    {
        return $this->getProduct()->getInventory()->getEnabled()
            && !$this->getProduct()->getInventory()->isOutOfStock();
    }

    // {{{ Tabs

    /**
     * Get tabs 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getTabs()
    {
        if (!isset($this->tabs)) {
            $list = $this->defineTabs();
            $i = 0;
            foreach ($list as $k => $data) {
                $list[$k] = array(
                    'id'      => 'product-details-tab-' . $i,
                    'name'    => $k,
                );

                if (is_string($data)) {
                    $list[$k]['template'] = $data;

                } elseif (is_array($data) && isset($data['template'])) {
                    $list[$k]['template'] = $data['template'];

                } elseif (is_array($data) && isset($data['list'])) {
                    $list[$k]['list'] = $data['list'];

                } elseif (is_array($data) && isset($data['widget'])) {
                    $list[$k]['widget'] = $data['widget'];

                } else {
                    unset($list[$k]);
                }

                $i++;
            }

            $this->tabs = $list;
        }

        return $this->tabs;
    }

    /**
     * Define tabs 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function defineTabs()
    {
        $list = array();

        if ($this->hasDescription()) {
            $list['Description'] = array(
                'list' => 'product.details.page.tab.description'
            );
        }

        return $list;
    }

    /**
     * Get tab class 
     * 
     * @param integer $index Tab index
     * @param array   $tab   Tab
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getTabClass($index, array $tab)
    {
        return 0 == $index ? 'active' : '';
    }

    /**
     * Check - product has Description tab or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function hasDescription()
    {
        return 0 < strlen($this->getProduct()->getDescription())
            || $this->hasAttributes();
    }

    /**
     * Check - product has visible attributes or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function hasAttributes()
    {
        return 0 < $this->getProduct()->getWeight()
            || 0 < strlen($this->getProduct()->getSku());
    }

    // }}}
}
