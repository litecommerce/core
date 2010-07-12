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

namespace XLite\Module\FeaturedProducts\View;

/**
 * Featured products widget 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class FeaturedProducts extends \XLite\View\ProductsList
{
    /**
     *  Widget parameter names
     */
    const PARAM_CATEGORY_ID = 'category_id';


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Featured products';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_CATEGORY_ID => new \XLite\Model\WidgetParam\ObjectId\Category('Category ID', 0, false),
        );

        $this->requestParams[] = self::PARAM_CATEGORY_ID;

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue($this->config->FeaturedProducts->featured_products_look);

        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_THUMBNAIL]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue(5);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
    }

    /**
     * Return products list
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        $featuredProducts = $this->getCategory()->getFeaturedProducts();
        $products = array();

        if (is_array($featuredProducts)) {
            foreach ($featuredProducts as $fp) {
                $products[] = $fp->get('product');
            }
        }

        return $products;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && !$this->get('page');
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';
        $result[] = 'category';
    
        return $result;
    }
}
