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

/**
 * Featured products widget 
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_Module_FeaturedProducts_View_FeaturedProducts extends XLite_View_ProductsListContainer
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
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
        return 'modules/FeaturedProducts/featured_products';
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

        $this->widgetParams['displayModeAdjustable']->set('value', 0);
        $this->widgetParams['allItemsPerPage']->set('value', 1);

        unset($this->widgetParams['sortCriterionAdjustable'], $this->widgetParams['sortCriterion'], $this->widgetParams['sortOrder']);
    }

    /**
     * Get products array
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts($sortCriterion = 'name', $sortOrder = 'asc')
    {
        $featuredProducts = $this->getCategory()->getFeaturedProducts();

        $products = array();
        if (is_array($featuredProducts)) {
            foreach ($featuredProducts as $fp) {
                $products[] = $fp->product;
            }
        }

        return $products;
    }

    /**
     * Export widget arguments 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function exportWidgetArguments()
    {
        $data = parent::exportWidgetArguments();

        $data['sortCriterionAdjustable'] = 0;

        return $data;
    }

}
