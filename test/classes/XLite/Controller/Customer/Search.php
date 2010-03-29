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

/**
 * Products search
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Search extends XLite_Controller_Customer_Abstract
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'substring');    

    /**
     * Products list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $products = null;

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */ 
    protected function getLocation()
    {       
        return 'Search Results';
    }

    /**
     * Initialize controller
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        if (!isset(XLite_Core_Request::getInstance()->action)) {
            $this->session->set('productListURL', $this->getUrl());
        }
    }
    
    /**
     * Get products list
     * 
     * @return array of XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts()
    {
        if (is_null($this->products)) {

            $p = new XLite_Model_Product();
            $this->products = $p->advancedSearch($this->get('substring'), '', 0, true, false, true);
            if ($this->get('pageID') == null) {
                $searchStat = new XLite_Model_SearchStat();
                $searchStat->add($this->get('substring'), count($this->products));
            }    
        }

        return $this->products;
    }
}

