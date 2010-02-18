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
    protected function getHead()
    {
        return 'Your shopping cart';
    }

    protected function getDir()
    {
        $dir = 'mini_cart';

        if ($this->use_dir) {
            $dir = $this->use_dir;
        }

        return $dir;
    }

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
        return $this->cart->getItemsCount() > 3;
    }

    /**
     * Return up to 3 items from cart
     * 
     * @return array array of cart items
     */
    protected function getItemsList()
    {
       if (is_null($this->itemsList)) {
           $this->itemsList = array_slice($this->cart->getItems(), 0, min(3, $this->cart->getItemsCount()));
       }

       return $this->itemsList;
    }

    /**
     * Get cart total 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSums()
    {
        return array(
            'Total' => $this->cart->get('total'),
        );
    }

}

