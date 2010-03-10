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


// FIXME - related templates must be deleted

/**
 * RelatedProducts widget
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_Module_ProductAdviser_View_RelatedProducts extends XLite_View_ProductsList
{
	/**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('product');

	/**
	 * Get widget title
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getHead()
	{
		return 'Related products';
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

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue($this->config->ProductAdviser->rp_template);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue($this->config->ProductAdviser->rp_columns);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue($this->config->ProductAdviser->rp_show_descr);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue($this->config->ProductAdviser->rp_show_price);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue($this->config->ProductAdviser->rp_show_buynow);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        foreach ($this->getHiddenParamsList() as $param) {
            $this->widgetParams[$param]->setVisibility(false);
        }
    }

    /**
     * Get the list of parameters that are hidden on the settings page 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getHiddenParamsList()
    {
        return array(
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR,
            self::PARAM_SHOW_SORT_BY_SELECTOR,
            self::PARAM_SORT_BY,
            self::PARAM_SORT_ORDER,
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE,
        );
    }

    /**
     * Return products list
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getData()
    {
        return $this->getProduct()->getRelatedProducts();
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
        return parent::isVisible() && $this->config->ProductAdviser->related_products_enabled;
    }

}
