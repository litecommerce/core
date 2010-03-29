<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Minicart widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Side bar with minicart
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Minicart extends XLite_View_SideBarBox
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
        return array_slice($this->getCart()->getItems(), 0, min(self::ITEMS_TO_DISPLAY, $this->getCart()->getItemsCount()));
    }

    /**
     * Return up to 3 items from Wishlist
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getWishlistItems()
    {
        $wishlist = $this->get('wishlist');
        return $wishlist ? array_slice($wishlist->getProducts(), 0, min(self::ITEMS_TO_DISPLAY, $this->countWishlistProducts())) : array();
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
        return self::ITEMS_TO_DISPLAY < $this->getCart()->getItemsCount();
    }

    /**
     * Check whether in Wislist there are more than 3 items
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isWishlistTruncated()
    {
        return self::ITEMS_TO_DISPLAY < $this->countWishlistProducts();
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
        return ($this->getCart()->isEmpty()) ? 'empty' : 'collapsed';
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
        return array('Total' => $this->getCart()->get('total'));
    }

    /**
     * Count products in the wish list
     *
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function countWishlistProducts()
    {
        return ($wishlist = $this->get('wishlist')) ? count($wishlist->getProducts()) : 0;
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
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
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
        return array_merge(parent::getCSSFiles(), array('mini_cart/minicart.css'));
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
        return array_merge(parent::getJSFiles(), array('mini_cart/minicart.js'));
    }
}

