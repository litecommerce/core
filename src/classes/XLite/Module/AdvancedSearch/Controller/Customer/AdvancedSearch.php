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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\AdvancedSearch\Controller\Customer;

/**
 * Advanced product search
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AdvancedSearch extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'submode', 'substring');

    /**
     * Products list (cache)
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $products = null;

    /**
     * Search conditions
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $search = null;

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Advanced search';
    }

    function init()
    {
        if (is_null($this->session->get('search')) && $this->auth->is('logged')) {
            $this->session->set('search', unserialize($this->auth->getProfile()->get('search_settings')));
        }

        parent::init();

        if ($this->getComplex('properties.search')) {
            $this->session->set('search', $this->getComplex('properties.search'));
        }

        $this->search = $this->session->get('search');
        if (
            is_null($this->search)
            || !is_array($this->search)
            || $this->session->get('quick_search')
        ) {
            $this->search['substring'] = $this->session->get('quick_search');
            $this->session->set('quick_search', null);
            $this->search['logic'] = 1;
            $this->search['title'] = 1;
            $this->search['brief_description'] = 1;
            $this->search['description'] = 1;
            $this->search['meta_tags'] = 1;
            $this->search['extra_fields'] = 1;
            $this->search['options'] = 1;
            $this->search['subcategories'] = 1;
        };
    }
    
    /**
     * Save search conditions 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function action_save_filters()
    {
        $this->auth->getProfile()->set('search_settings', serialize($this->session->get('search')));
        $this->auth->getProfile()->update();
    }

    /**
     * Get products list
     * 
     * @return array of \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts()
    {
        if (is_null($this->products)) {
            $this->products = array();

            $properties = $this->getComplex('properties.search');
            if (is_null($properties)) {
                $properties = $this->session->get('search');
            }

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
                    if (empty($properties[$key])) {
                        $properties[$key] = null;

                    } else {
                        $properties[$key] = addslashes($properties[$key]);
                    }
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
                    $orderby = 'price';
                }

                if (isset($properties['weight'])) {
                    $weight = explode(',', $properties['weight'], 2);
                    $properties['start_weight'] = $weight[0];
                    $properties['end_weight'] = (isset($weight[1]) && !empty($weight[1])) ? $weight[1] : null;
                    $orderby = 'weight';
                }

                $this->products = $p->_advancedSearch(
                    $properties['substring'],
                    $orderby,
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

                $searchStat = new \XLite\Model\SearchStat();
                $searchStat->add($properties['substring'], count($this->products));
            }
        }

        return $this->products;
    }

    /**
     * Get page title
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Advanced search';
    }
}

