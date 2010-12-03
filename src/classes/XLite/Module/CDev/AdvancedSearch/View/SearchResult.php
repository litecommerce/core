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

namespace XLite\Module\CDev\AdvancedSearch\View;

/**
 * Search result
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SearchResult extends \XLite\View\SearchResult implements \XLite\Base\IDecorator
{
    /**
     * searchParams 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $searchParams = null;


    /**
     * Get products list
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        if (is_null($this->data)) {
            $this->data = array();

            if ('advanced_search' == \XLite\Core\Request::getInstance()->target) {
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
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && ('advanced_search' != \XLite\Core\Request::getInstance()->target || 'found' == \XLite\Core\Request::getInstance()->submode);
    }


    /**
     * getSearchParamValue
     * 
     * @param string $name Param name
     *  
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getSearchParamValue($name)
    {
        if (!isset($this->searchParams)) {
            $this->searchParams = \XLite\Core\Session::getInstance()->get('search');
            if (!is_array($this->searchParams)) {
                $this->searchParams = array();
            }
        }

        return empty($this->searchParams[$name]) ? null : $this->searchParams[$name];
    }

    /**
     * Search products 
     * FIXME
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
            \XLite\Core\Request::getInstance()->submode == 'found'
            && !$isDumpSearch
        ) {
            $p = new \XLite\Model\Product();

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
                                                            
            if (isset($properties['price'])) {
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
                $this->getSearchParamValue('substring'),
                $this->getParam(self::PARAM_SORT_BY) . ' ' . strtoupper($this->getParam(self::PARAM_SORT_ORDER)),
                $this->getSearchParamValue('sku'),
                $this->getSearchParamValue('category'),
                $this->getSearchParamValue('subcategories'),
                true,
                $this->getSearchParamValue('logic'),
                $this->getSearchParamValue('title'),
                $this->getSearchParamValue('description'),
                $this->getSearchParamValue('brief_description'),
                $this->getSearchParamValue('meta_tags'),
                $this->getSearchParamValue('extra_fields'),
                $this->getSearchParamValue('options'),
                $this->getSearchParamValue('start_price'),
                $this->getSearchParamValue('end_price'),
                $this->getSearchParamValue('start_weight'),
                $this->getSearchParamValue('end_weight')
            );

            $searchStat = new \XLite\Model\SearchStat();
            $searchStat->add($this->getSearchParamValue('substring'), count($this->data));
        }
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
        $result[] = 'advanced_search';

        return $result;
    }
}

