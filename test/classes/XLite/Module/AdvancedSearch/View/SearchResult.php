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
 * Search result
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_AdvancedSearch_View_SearchResult extends XLite_View_SearchResult implements XLite_Base_IDecorator
{
    /**
     * Initialize
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init(array $attributes = array())
    {
        parent::init($attributes);

        $this->allowedTargets[] = 'advanced_search';
    }

    /**
     * Get products list
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getData()
    {
        if (is_null($this->data)) {
            $this->data = array();

            if ('advanced_search' == XLite_Core_Request::getInstance()->target) {
                $this->searchProducts();

            } else {
                parent::getData();
            }
        }

        return $this->data;
    }

    /**
     * Check widget visibility
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && ('advanced_search' != XLite_Core_Request::getInstance()->target || 'found' == XLite_Core_Request::getInstance()->submode);
    }

    /**
     * Search products 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function searchProducts()
    {
        $properties = $this->session->get('search');

        if (!is_array($properties)) {
            $properties = array();
        }

        $isDumpSearch = !empty($properties['substring'])
            && !isset($properties['title'])
            && !isset($properties['brief_description'])
            && !isset($properties['description'])
            && !isset($properties['meta_tags'])
            && !isset($properties['extra_fields'])
            && !isset($properties['options']);

        if (
            XLite_Core_Request::getInstance()->submode == 'found'
            && !$isDumpSearch
        ) {
            $p = new XLite_Model_Product();

            foreach ($properties as $key => $value) {
                $properties[$key] = empty($properties[$key]) ? null : addslashes($properties[$key]);
            }

            $booleanProperties = array(
                'title', 'description', 'brief_description', 'subcategories', 'meta_tags',
                'extra_fields', 'options'
            );

            foreach ($booleanProperties as $key) {
                $properties[$key] = isset($properties[$key]);
            }

            $orderby = null;
                                                            
            if (isset($properties["price"])) {
                $price = explode(',', $properties['price'], 2);
                $properties['start_price'] = $price[0];
                $properties['end_price'] = (isset($price[0]) && !empty($price[1])) ? $price[1] : null;
            }

            if (isset($properties['weight'])) {
                $weight = explode(',', $properties['weight'], 2);
                $properties['start_weight'] = $weight[0];
                $properties['end_weight'] = (isset($weight[1]) && !empty($weight[1])) ? $weight[1] : null;
            }

            $this->data = $p->_advancedSearch(
                $properties['substring'],
                $this->getParam(self::PARAM_SORT_BY) . ' ' . strtoupper($this->getParam(self::PARAM_SORT_ORDER)),
                $properties['sku'],
                isset($properties['category']) ? $properties['category'] : null,
                $properties['subcategories'],
                true,
                $properties['logic'],
                $properties['title'],
                $properties['description'],
                $properties['brief_description'],
                $properties['meta_tags'],
                $properties['extra_fields'],
                $properties['options'],
                isset($properties['start_price'])  ? $properties['start_price'] : null,
                isset($properties['end_price'])    ? $properties['end_price'] : null,
                isset($properties['start_weight']) ? $properties['start_weight'] : null,
                isset($properties['end_weight'])   ? $properties['end_weight'] : null
            );

            $searchStat = new XLite_Model_SearchStat();
            $searchStat->add($properties['substring'], count($this->data));
        }
    }
}

