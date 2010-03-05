<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Featured Products widget
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
 * Featured products widget 
 * 
 * @package   View
 * @subpackage Widget
 * @since      3.0.0 EE
 */
class XLite_Module_FeaturedProducts_View_FeaturedProducts extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('main', 'category');


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Featured products';
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
        return 'modules/FeaturedProducts/featured_products/' . $this->getDisplayMode();
    }

    protected function getDisplayMode()
    {
        return ($this->attributes[self::IS_EXPORTED] ? $this->attributes['displayMode'] : $this->config->FeaturedProducts->featured_products_look);
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getCategory()->getFeaturedProducts() && !$this->get('page');
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
        
        $this->widgetParams += XLite_View_ProductsList::getWidgetParamsList();
    }

    /**
     * Get products array
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts()
    {
        $products = array();
        $featuredProducts = $this->getCategory()->getFeaturedProducts();

        if (is_array($featuredProducts)) {
            foreach ($featuredProducts as $fp) {
                $products[] = $fp->product;
            }
        }
        return $products;
    }

    /**
     * Get widget arguments (used by the common products list template)
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWidgetArguments()
    {
        return (isset($this->attributes['widgetArguments']) ? $this->attributes['widgetArguments'] : array());
    }

}

