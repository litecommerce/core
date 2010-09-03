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
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Minicart widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Minicart extends \XLite\View\SideBarBox
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE = 'displayMode';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_VERTICAL   = 'vertical';
    const DISPLAY_MODE_HORIZONTAL = 'horizontal';

    /**
     * Number of cart items to display by default 
     */
    const ITEMS_TO_DISPLAY = 3;


    /**
     * Widget directories 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_VERTICAL   => 'Vertical',
        self::DISPLAY_MODE_HORIZONTAL => 'Horizontal',
    );

    /**                                                
     * Return title                                    
     *                                                 
     * @return string                                  
     * @access protected                               
     * @since  3.0.0                                
     */                                                
    protected function getHead()                       
    {
        return 'Your shopping cart';
    }

    /**
     * Get widget templates directory
     *                               
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'mini_cart/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Return up to 3 items from cart
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getItemsList()
    {
        return array_slice(
            $this->getCart()->getItems()->toArray(),
            0,
            min(self::ITEMS_TO_DISPLAY, $this->getCart()->countItems())
        );
    }

    /**
     * Check whether in cart there are more than 3 items
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTruncated()
    {
        return self::ITEMS_TO_DISPLAY < $this->getCart()->countItems();
    }

    /**
     * Return a CSS class depending on whether the minicart is empty or collapsed
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCollapsed()
    {
        return $this->getCart()->isEmpty() ? 'empty' : 'collapsed';
    }

    /**
     * Get cart total
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTotals()
    {
        return array('Total' => $this->getCart()->getTotal());
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', self::DISPLAY_MODE_VERTICAL, true, $this->displayModes
            ),
        );
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'mini_cart/minicart.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'js/core.controller.js';
        $list[] = 'js/core.loadable.js';
        $list[] = 'js/jquery.blockUI.js';
        $list[] = 'mini_cart/minicart.js';

        return $list;
    }
}

