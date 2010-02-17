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
	 * Title
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $head = 'Shopping cart';

	/**
	 * Widget directory
	 * 
	 * @var    string
	 * @access protected
	 * @since  1.0.0
	 */
	protected $dir = 'mini_cart';

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
     * Initilization
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();

        if ($this->use_dir) {
            $this->dir = $this->use_dir;
        }
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

        $dirs = new XLite_Model_WidgetParam_List('use_dir', 'mini_cart', 'Display mode');
        $dirs->options = $this->dirs;

        $this->widgetParams[] = $dirs;
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

        if (!isset($attributes['use_dir']) || !isset($this->dirs[$attributes['use_dir']])) {
            $errors['use_dir'] = 'Display mode has wrong value!';
        }

		return $errors;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array list of css files required to display the widget
     * @access public
     */
    public function getCSSFiles() {
        return array('mini_cart/vertical.css');
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array list of js files required to display the widget
     * @access public
     */
    public function getJSFiles() {
        return array('mini_cart/minicart.js');
    }

    /**
     * Check whether in cart there are more than 3 items
     *
     * @retun boolean
     */
    protected function getIsTruncated() {
        return $this->getComplex('cart.itemsCount') > 3;
    }

    /**
     * Return up to 3 items from cart
     * 
     * @return array array of cart items
     */
    protected function getItemsList() {
       if (is_null($this->itemsList)) {
           $cart = $this->cart;
           $this->itemsList = array_slice($cart->getItems(), 0, min(3, $cart->getItemsCount()));
       }

       return $this->itemsList;
    }

    protected function getSums() {
        return array(
                'Total' => $this->getComplex('cart.total'),
            );
    }

}

