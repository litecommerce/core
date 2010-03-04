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
 * @since     3.0.0 EE
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
        'vertical'   => 'Vertical',
        'horizontal' => 'Horizontal',
    );

    /**                                                
     * Return title                                    
     *                                                 
     * @return string                                  
     * @access protected                               
     * @since  3.0.0 EE                                
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
        return 'mini_cart/' . $this->attributes['displayMode'];
    }

    /**
     * Return up to 3 items from cart
     * 
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getItemsList()
    {
        return array_slice($this->getCart()->getItems(), 0, min(self::ITEMS_TO_DISPLAY, $this->getCart()->getItemsCount()));
    }

    /**
     * Check whether in cart there are more than 3 items
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function isTruncated()
    {
        return self::ITEMS_TO_DISPLAY < $this->getCart()->getItemsCount();
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
            'displayMode' => new XLite_Model_WidgetParam_List('Display mode', 'vertical', $this->displayModes),
        );
    }

    /**
     * Get a list of CSS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0 EE
     */
    public function getCSSFiles()
    {
        return array('mini_cart/minicart.css');
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function getJSFiles()
    {
        return array('mini_cart/minicart.js');
    }
}

