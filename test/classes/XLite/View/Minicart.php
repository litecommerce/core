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
     * Widget directories 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $dirs = array(
        'mini_cart'        => 'Vertical',
        'mini_cart_dialog' => 'Horizontal',
    );

    /**
     * Items to be shown in the minicart block
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     */ 
    protected $itemsList = NULL;


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
        return $this->attributes['use_dir'];
    }

    /**
     * Return cart instance 
     * 
     * @return XLite_Model_Order
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCart()
    {
        return XLite_Model_CachingFactory::getObject('XLite_Model_Cart');
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
        return array_slice($this->getCart()->getItems(), 0, min(3, $this->getCart()->getItemsCount()));
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
        return 3 < $this->getCart()->getItemsCount();
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

        // FIXME - use camel case
        $dirs = new XLite_Model_WidgetParam_List('use_dir', 'mini_cart', 'Display mode');
        $dirs->options = $this->dirs;

        $this->widgetParams[] = $dirs;
    }

    /**
     * Define some attribute
     *
     * @param array $attributes attributes to set
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['use_dir'] = 'mini_cart';

        parent::__construct($attributes);
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attrs attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $conditions = array(
            array(
                self::ATTR_CONDITION => !isset($attrs['use_dir']) || !isset($this->dirs[$attrs['use_dir']]),
                self::ATTR_MESSAGE   => 'Display mode is not set',
            ),
        );

        return parent::validateAttributes($attrs) + $this->checkConditions($conditions);
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
        return array('mini_cart/vertical.css');
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

