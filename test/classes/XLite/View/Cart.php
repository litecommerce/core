<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Cart widget
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
 * Cart widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Cart extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('cart');


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Shopping cart';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return 'shopping_cart';
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

        if ($this->getCart()->isEmpty()) {
            $this->widgetParams[self::PARAM_TEMPLATE]->setValue($this->getDir() . '/empty.tpl');
        }
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
        return array_merge(
            parent::getCSSFiles(),
            array(
                $this->getDir() . '/cart.css',
            )
        );
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/cart.js';

        return $list;
    }

}

