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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Menu;

/**
 * Abstract menu 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AMenu extends \XLite\View\AView
{
    /**
     * Items 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $items;

    /**
     * Get menu items 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItems()
    {
        if (!isset($this->items)) {
            $this->items = $this->defineItems();

            foreach ($this->items as $k => $v) {
                $v['active'] = $this->isActiveItem($v);
            }
        }

        return $this->items;
    }

    /**
     * Check - specified item is active or not
     * 
     * @param array $item Menu item
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isActiveItem(array $item)
    {
        return isset($v['target']) ? \XLite::getTarget() == $v['target'] : false;
    }

    /**
     * Define menu items 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function defineItems();

    /**
     * Display item class as tag attribute
     * 
     * @param integer $i Item index
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function displayItemClass($i)
    {
        $classes = array('leaf');

        if (0 == $i) {
            $classes[] = 'first';
        }

        if (count($this->getItems()) == $i + 1) {
            $classes[] = 'last';
        }

        return $classes ? ' class="' . implode(' ', $classes) . '"' : '';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getItems();
    }
}
